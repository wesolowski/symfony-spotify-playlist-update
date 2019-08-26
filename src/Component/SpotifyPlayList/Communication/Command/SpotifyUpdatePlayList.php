<?php declare(strict_types=1);


namespace App\Component\SpotifyPlayList\Communication\Command;


use App\Component\SpotifyPlayList\Business\EskaPlayListFacadeInterface;
use App\Component\SpotifyPlayList\Business\Page\SongPageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;


class SpotifyUpdatePlayList extends Command
{
    protected static $defaultName = 'spotify:playlist:update';

    /**
     * @var EskaPlayListFacadeInterface
     */
    private $eskaPlayListFacade;

    /**
     * @var SongPageInterface[]
     */
    private $playListInfos;

    /***
     * @param EskaPlayListFacadeInterface $eskaPlayListFacade
     * @param SongPageInterface[] ...$playListInfos
     */
    public function __construct(EskaPlayListFacadeInterface $eskaPlayListFacade, $playListInfos)
    {
        $this->eskaPlayListFacade = $eskaPlayListFacade;
        $this->playListInfos = $playListInfos;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $defaultReceiverName = 1 === \count($this->playListInfos) ? current($this->playListInfos) : null;
        $this
            ->setDefinition([
                new InputArgument('playlist', InputArgument::IS_ARRAY, 'Names of the receivers/transports to consume in order of priority', $defaultReceiverName ? [$defaultReceiverName] : []),
            ])
            ->setDescription('Consumes messages')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command consumes messages and dispatches them to the message bus.
    <info>php %command.full_name% <receiver-name></info>
To receive from multiple transports, pass each name:
    <info>php %command.full_name% playListName</info>
    <info>php %command.full_name% <receiver-name> --playlist=playListName</info>
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $names = [];
        foreach ($this->playListInfos as $playListInfo) {
            $names[] = $playListInfo->getConsoleName();
        }

        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);
        if ($names && 0 === \count($input->getArgument('playlist'))) {
            $io->block('Which playlist do you want to update?', null, 'fg=white;bg=blue', ' ', true);
            $io->writeln('Choose which playlist you want.');
            if (\count($names) > 1) {
                $io->writeln(sprintf('Hint: to consume from multiple, use a list of their names, e.g. <comment>%s</comment>', implode(', ', $names)));
            }
            $question = new ChoiceQuestion('Select receivers to consume:', $names, 0);
            $question->setMultiselect(true);
            $input->setArgument('playlist', $io->askQuestion($question));
        }
        if (0 === \count($input->getArgument('playlist'))) {
            throw new RuntimeException('Please pass at least one receiver.');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($receiverNames = $input->getArgument('playlist') as $receiverName) {
            dump($receiverName);
            foreach ($this->playListInfos as $playListInfo) {
                if (strtolower($receiverName) === strtolower($playListInfo->getConsoleName())) {
                    $this->eskaPlayListFacade->updatePlayList($playListInfo);
                }
            }
        }
    }
}