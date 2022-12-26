<?php

namespace ModulesGarden\Servers\AwsEc2\App\Cron;

use ModulesGarden\Servers\AwsEc2\Core\CommandLine\CommandLoop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Sample
 * @package ModulesGarden\Servers\AwsEc2\App\Cron
 */
class SampleLoop extends CommandLoop
{
    /**
     * Command name
     * @var string
     */
    protected $name = 'sample:loop';

    /**
     * Command description
     * @var string
     */
    protected $description  = 'Ooo sample command loop?';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'I am not going to help you!';

    /**
     * @var int
     */
    protected $loopInterval = 60;

    /**
     * Set up custom parameters
     */
    protected function setup()
    {

    }

    /**
     * This method will be called in the loop based on $loopInterval
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param SymfonyStyle $io
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        for($i=0; $i < 10; $i++)
        {
            $output->writeln("Do...");
            sleep(1);
        }

        $output->writeln("Sleeep...");
        sleep(2);
    }
}