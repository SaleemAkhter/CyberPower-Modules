<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Queue;

use ModulesGarden\Servers\HetznerVps\Core\Queue\Job\ChildrenTrait;
use ModulesGarden\Servers\HetznerVps\Core\Queue\Services\Log;

/**
 * Class Job
 * @package ModulesGarden\Servers\HetznerVps\Core\Queue
 */
class Job implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use ChildrenTrait;

    /**
     * @var Models\Job
     */
    protected $model;

    /**
     * @var Services\Log
     */
    protected $log;

    /**
     * Job constructor.
     * @param Models\Job $job
     * @param Log $log
     */
    public function __construct(Models\Job $job, Services\Log $log)
    {
        $this->model    = $job;
        $this->log      = $log;
    }

    /**
     *
     */
    public function handle()
    {
        $this->log->info('Override me please!');
    }
}
