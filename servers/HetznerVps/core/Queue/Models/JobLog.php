<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Queue\Models;

use ModulesGarden\Servers\HetznerVps\Core\Models\ExtendedEloquentModel;

/**
 * Class Job
 * @package ModulesGarden\Servers\HetznerVps\Core\Job\Models
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