<?php

namespace ModulesGarden\Servers\AwsEc2\App\Cron;

use ModulesGarden\Servers\AwsEc2\Core\CommandLine\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Sample
 * @package ModulesGarden\Servers\AwsEc2\App\Cron
 */
class Sample extends Command
{
    /**
     * Command name
     * @var string
     */
    protected $name = 'sample:test';

    /**
     * Command description
     * @var string
     */
    protected $description  = 'Ooo sample command?';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'I am not going to help you!';

    /**
     * Configure command
     */
    protected function setup()
    {
        $this
            ->addArgument('run', InputArgument::REQUIRED, 'Should we run that command?')
            ->addArgument('test', InputArgument::OPTIONAL,'Enable test mode')
            ->addOption('testoption', 't', InputOption::VALUE_OPTIONAL, 'What about that?');
    }

    /**
     * Run your custom code
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $run        = $input->getArgument('run');
        $test       = $input->getArgument('test');
        $testoption = $input->getOption('testoption');

        /**
         * Some simple output
         */
        $output->write('Some text.').
        $output->writeln('Some text with new line after');
        $output->writeln('Run: '.$run);
        $output->writeln('Test:' .$test);
        $output->writeln('TestOption', $testoption);

        /**
         * Some elements
         *
         */
        $io->title('Title!');
        $io->section('This is section');
        $io->text('This is text');
        $io->table([
            'Header 1',
            'Header 2'
        ], [[
            'Cell 1',
            'Cell 2'
        ]]);

        /**
         * Progress Bar
         */
        $io->title('Progress Bar!');
        $io->progressStart(100);

        $i = 0;
        while($i < 80)
        {
            $io->progressAdvance(10);

            $i += 10;

            sleep(1);
        }

        $io->progressFinish();

        $io->error('Ohh no!');
        $io->success('Ohh yeah!');
        $io->warning('Ohh no?');

        /**
         * More elements you will find here: https://symfony.com/doc/current/console/style.html
         */
    }
}