<?php

namespace ModulesGarden\WordpressManager\Core\Queue\Models;

use ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

/**
 * Class Job
 * @package ModulesGarden\WordpressManager\Core\Job\Models
 * @property $job job id
 * @property $jobId
 * @property $id
 * @property $date -
 * @property $message -
 * @property $type
 * @property $additional - serialized
 */
class JobLog extends ExtendedEloquentModel
{
    /**
     * @var string
     */
    protected $table    = 'JobLog';

    public function job()
    {
        return $this->hasOne(Job::class, 'job_id');
    }
}