<?php declare(strict_types=1);

namespace App\Component\EskaPlayList\Communication\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EskaGoraca20 extends Command
{
    protected static $defaultName = 'spotify:eska:update';

    protected function configure()
    {
        $this->setDescription('Update Eska-goraca20 playlist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}