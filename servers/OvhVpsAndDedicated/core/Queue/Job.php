<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Queue;

/**
 * Class Job
 * @package ModulesGarden\Servers\OvhVpsAndDedicated\Core\Queue
 */
class Job implements \Illuminate\Contracts\Queue\ShouldQueue
{
    /**
     * @var Models\Job
     */
    protected $model;

    /**
     * @var Services\Log
     */
    protected $log;

    /**
     * @param Models\Job $job
     */
    public function setJobModel(\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Queue\Models\Job $job)
    {
        $this->model    = $job;

        $this->log      = new Services\Log($this->model);
    }

    /**
     *
     */
    public function handle()
    {
        $this->log->info('Override me please!');
    }
}
