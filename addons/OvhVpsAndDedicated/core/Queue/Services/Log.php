<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Queue\Services;


use ModulesGarden\OvhVpsAndDedicated\Core\Queue\Models\JobLog;

class Log
{
    const SUCCESS   = 'success';
    const ERROR     = 'error';
    const INFO      = 'info';

    /**
     * @var Models\Job
     */
    protected $job;

    public function __construct(\ModulesGarden\OvhVpsAndDedicated\Core\Queue\Models\Job $job)
    {
        $this->job  = $job;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function success($message, $additional = null)
    {
        $this->log(self::SUCCESS, $message, $additional);

        return $this;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function error($message, $additional = null)
    {
        $this->log(self::ERROR, $message, $additional);

        return $this;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function info($message, $additional = null)
    {
        $this->log(self::INFO, $message, $additional);

        return $this;
    }

    /**
     * @param $type
     * @param $message
     * @param null $additional
     * @return $this
     */
    protected function log($type, $message, $additional = null)
    {
        try
        {
            $model              = new JobLog();
            $model->job_id      = $this->job->id;
            $model->type        = $type;
            $model->message     = $message;
            $model->additional  = serialize($additional);
            $model->save();
        }
        catch (\Exception $ex)
        {
            var_dump($ex->getMessage());
        }


        return $this;
    }
}
