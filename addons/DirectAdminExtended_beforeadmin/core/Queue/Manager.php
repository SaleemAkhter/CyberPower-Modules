<?php

namespace ModulesGarden\DirectAdminExtended\Core\Queue;

use ModulesGarden\DirectAdminExtended\Core\DependencyInjection\DependencyInjection;
use ModulesGarden\DirectAdminExtended\Core\Queue\Services\Log;

class Manager
{
    /**
     * @var Models\Job
     */
    protected $job;

    /**
     * @var Log
     */
    protected $log;

    public function __construct(\ModulesGarden\DirectAdminExtended\Core\Queue\Models\Job $job)
    {
        $this->job  = $job;

        $this->log  = new Log($this->job);
    }

    public function fire()
    {
        try
        {
            $this->job->setRunning();

            $ret    = $this->resolveAndFire($this->job->job, $this->job->data);
            if($ret !== false)
            {
                $this->job->setFinished();
            }
        }
        catch(\Exception $ex)
        {
            //Set error in job
            $this->job->setError();
            $this->job->setRetryAfter(date('Y-m-d H:i:s', strtotime('+ 60 seconds')));
            $this->job->increaseRetryCount();

            //add log message
            $this->log->error($ex->getMessage(), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
        }
    }

    protected function resolveAndFire($job, $data)
    {
        list($class, $method)   = $this->parseJob($job);

        $instance               = $this->resolve($class);

        if(method_exists($instance, 'setJobModel'))
        {
            $instance->setJobModel($this->job);
        }

        return call_user_func_array([$instance, $method], unserialize($data));
    }

    /**
     * Resolve the given job handler.
     *
     * @param  string  $class
     * @return mixed
     */

    protected function resolve($class)
    {
        return DependencyInjection::create($class);
    }

    /**
     * Parse the job declaration into class and method.
     *
     * @param  string  $job
     * @return array
     */

    protected function parseJob($job)
    {
        $segments = explode('@', $job);
        return count($segments) > 1 ? $segments : array($segments[0], 'fire');
    }
}