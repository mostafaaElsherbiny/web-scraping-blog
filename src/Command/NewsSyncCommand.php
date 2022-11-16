<?php

namespace App\Command;

use Hightload;
use Carbon\Carbon;
use App\Scraper\Scraper;
use App\Message\NewsSync;
use App\Source\Politifact;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Message\OrderConfirmationEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewsSyncCommand extends Command
{

    protected static $defaultName = 'app:news:sync';

    public function __construct(public MessageBusInterface $queue)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('sync all news');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->queue->dispatch(new NewsSync('hi get new blogs'));
        $io->note('syncing now');
        return 0;
    }
}
