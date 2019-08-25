<?php declare(strict_types=1);

namespace App\Component\SpotifyPlayList\Communication\Command;

use App\Component\SpotifyPlayList\Business\EskaPlayListFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EskaGoraca20 extends Command
{
    protected static $defaultName = 'spotify:eska:update';

    /**
     * @var EskaPlayListFacadeInterface
     */
    private $eskaPlayListFacade;

    /**
     * @param EskaPlayListFacadeInterface $eskaPlayListFacade
     */
    public function __construct(EskaPlayListFacadeInterface $eskaPlayListFacade)
    {
        $this->eskaPlayListFacade = $eskaPlayListFacade;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('Update Eska-goraca20 playlist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eskaPlayListFacade->updatePlayList();
    }
}