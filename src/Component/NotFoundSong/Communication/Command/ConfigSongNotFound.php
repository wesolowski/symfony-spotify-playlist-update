<?php declare(strict_types=1);


namespace App\Component\NotFoundSong\Communication\Command;

use App\Component\NotFoundSong\Business\NotFoundSongFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigSongNotFound extends Command
{
    protected static $defaultName = 'spotify:songs:config';

    /**
     * @var NotFoundSongFacadeInterface
     */
    private $notFoundSongFacade;

    /**
     * @param NotFoundSongFacadeInterface $notFoundSongFacade
     */
    public function __construct(NotFoundSongFacadeInterface $notFoundSongFacade)
    {
        $this->notFoundSongFacade = $notFoundSongFacade;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('create config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->notFoundSongFacade->getSongsInfo();
    }
}