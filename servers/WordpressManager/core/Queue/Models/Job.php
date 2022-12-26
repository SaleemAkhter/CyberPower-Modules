<?php

namespace ModulesGarden\WordpressManager\Core\Queue\Models;

use ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

/**
 * Class Job
 * @package ModulesGarden\WordpressManager\Core\Job\Models
 * @var $job
 * @var $data
 * @var $queue
 * @var $status
 *
 * @todo - obsługa dzieci
 */
class Job extends ExtendedEloquentModel
{
    const STATUS_RUNNING    = 'running';
    const STATUS_FINISHED   = 'finished';
    const STATUS_ERROR      = 'error';
    const STATUS_WAITING    = 'waiting';

    /**
     * @var string
     */
    protected $table    = 'Job';

    /**
     * @return JobLog::class[]
     */
    public function logs()
    {
        return $this->hasMany(JobLog::class, 'job_id');
    }
    /**
     * @return $this
     */
    public function setRunning()
    {
        $this->setStatus(self::STATUS_RUNNING);

        return $this;
    }

    /**
     * @return $this
     */
    public function setFinished()
    {
        $this->setStatus(self::STATUS_FINISHED);

        return $this;
    }

    /**
     * @return $this
     */
    public function setWaiting()
    {
        $this->setStatus(self::STATUS_WAITING);

        return $this;
    }

    /**
     * @return $this
     */
    public function setError()
    {
        $this->setStatus(self::STATUS_ERROR);

        return $this;
    }

    /**
     * @param $time
     * @return $this
     */
    public function setRetryAfter($time)
    {
        $this->retry_after  = $time;
        $this->save();

        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status   = $status;
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function increaseRetryCount()
    {
        $this->retry_count++;
        $this->save();

        return $this;
    }
}