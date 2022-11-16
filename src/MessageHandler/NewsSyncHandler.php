<?php

namespace App\MessageHandler;

use Carbon\Carbon;
use App\Message\News;
use App\Scraper\Scraper;
use App\Message\NewsSync;
use App\Source\Politifact;
use App\Repository\BlogRepository;
use App\Message\OrderConfirmationEmail;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsSyncHandler implements MessageHandlerInterface
{

    public function __construct(private BlogRepository $blogRepository)
    {
    }

    public function __invoke(NewsSync $newsSync)
    {
        $scraper = new Scraper;
        $source = new Politifact;
        $blogs = $scraper->scrap($source);
        foreach ($blogs as $key => $blog) {
            $storedBlog =  $this->blogRepository->findOneBy(['title' => $blog->getTitle()]);
            if ($storedBlog) {
                $storedBlog->setUpdatedAt(Carbon::now());
                $this->blogRepository->save($storedBlog, true);
            } else {
                $this->blogRepository->save($blog, true);
            }
        }

        echo "blogs sync successfully";
    }
}
