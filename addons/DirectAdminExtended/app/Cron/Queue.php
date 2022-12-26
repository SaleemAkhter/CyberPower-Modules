<?php

namespace ModulesGarden\DirectAdminExtended\App\Cron;

use ModulesGarden\DirectAdminExtended\App\Events\MyTestEvent;
use ModulesGarden\DirectAdminExtended\Core\CommandLine\AbstractCommand;
use function ModulesGarden\DirectAdminExtended\Core\Helper\fire;
use \ModulesGarden\DirectAdminExtended\Core\Queue\Models\Job;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Queue
 * @package ModulesGarden\DirectAdminExtended\App\Cron
 */
class Queue extends AbstractCommand
{
    /**
     * Command name
     * @var string
     */
    protected $name = 'queue';

    /**
     * Command description
     * @var string
     */
    protected $description  = 'Run module queue';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Just run that command to start all queued tasks!';

    /**
     * Configure command
     */
    protected function setup()
    {

    }

    /**
     * Run your custom code
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $queue  = new \ModulesGarden\DirectAdminExtended\Core\Queue\Queue();
        $queue->setCallBefore(function(Job $job) use($io){
            $io->writeln("Running {$job->job}");
        });
        $queue->process();
    }
}