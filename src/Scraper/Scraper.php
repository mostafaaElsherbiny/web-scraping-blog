<?php

namespace App\Scraper;

use Carbon\Carbon;
use App\Entity\Blog;
use Symfony\Component\Panther\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Scraper\Contracts\SourceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Scraper
{

    public function scrap(SourceInterface $source): Collection
    {
        $collection = [];
        $client = Client::createChromeClient();
        $crawler = $client->request('GET', $source->getUrl());
        $crawler->filter($source->getWrapperSelector())->each(function (Crawler $c) use ($source, &$collection) {
            $blog = new Blog();
            $title = $c->filter($source->getTitleSelector())->text();
            $blog->setTitle($title);
            $dateTime = substr($c->filter($source->getDateSelector())->text(), 10, 17);
            if ($dateTime) {
                $blog->setCreatedAt(Carbon::parse($dateTime));
            }

            $body = ($c->filter($source->getBodySelector())->text());
            $imageUrl = ($c->filter($source->getImageSelector())->attr('src'));
            $blog->setBody($body);
            $blog->setImage($imageUrl);

            $collection[] = $blog;
        });
        return new ArrayCollection($collection);
    }

    /**
     * In order to make DateTime work, we need to clean up the input.
     *
     * @throws \Exception
     */
    private function cleanupDate(string $dateTime): \DateTime
    {
        $dateTime = str_replace(['(', ')', 'UTC', 'at', '|'], '', $dateTime);

        return new \DateTime($dateTime);
    }
}
