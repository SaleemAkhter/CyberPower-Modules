<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Queue\Models;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;

/**
 * Class Job
 * @package ModulesGarden\OvhVpsAndDedicated\Core\Job\Models
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