<?php

namespace ModulesGarden\WordpressManager\App\Cron;

use ModulesGarden\WordpressManager\Core\CommandLine\AbstractCommand;
use ModulesGarden\WordpressManager\Core\Models\Logger\Model;
use ModulesGarden\WordpressManager\Core\Queue\Models\Job;
use ModulesGarden\WordpressManager\Core\Queue\Models\JobLog;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Queue
 * @package ModulesGarden\WordpressManager\App\Cron
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
    protected $description = 'Run module queue';

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
        $queue = new \ModulesGarden\WordpressManager\Core\Queue\Queue();
        $queue->setCallBefore(function (Job $job) use ($io) {
            $io->writeln("Running {$job->job}");
        });
        $queue->process();
        $this->clean();
    }

    protected function clean()
    {
        //Job
        Job::where('created_at', '<', date("Y-m-d H:i:s", strtotime("-1 months")))->delete();
        //Job Logs
        JobLog::where('created_at', '<', date("Y-m-d H:i:s", strtotime("-1 months")))->delete();
        //Logger
        Model::where('date', '<', date("Y-m-d H:i:s", strtotime("-1 months")))->delete();
    }
}