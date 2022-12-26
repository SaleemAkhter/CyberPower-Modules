<?php

namespace ModulesGarden\DirectAdminExtended\Core\Queue;

use Illuminate\Contracts\Queue\Queue;
use ModulesGarden\DirectAdminExtended\Core\Queue\Models\Job;

class DatabaseQueue implements Queue
{
    /**
     * @param $job
     * @param string $data
     * @param string $queue
     */
    public function push($job, $data = '', $queue = 'default')
    {
        $model          = new Job();
        $model->job     = $job;
        $model->data    = $data;
        $model->queue   = $queue;
        $model->save();
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {

    }

    public function later($delay, $job, $data = '', $queue = null)
    {

    }

    public function pushOn($queue, $job, $data = '')
    {

    }

    public function laterOn($queue, $delay, $job, $data = '')
    {

    }

    /**
     * @param string $queue
     * @return mixed
     */
    public function pop($queue = 'default')
    {
        return Job::where('queue', $queue)
                ->where(function($query){
                    $query->where('status', '=', '')
                        ->orWhere(function($query){
                            $query->where('status', '=', Job::STATUS_ERROR);
                            $query->whereRaw('retry_after < NOW()');
                            $query->where('retry_count', '<', '100');
                        })
                        ->orWhere(function($query){
                            $query->where('status', '=', Job::STATUS_WAITING);
                            $query->whereRaw('retry_after < NOW()');
                            $query->where('retry_count', '<', '100');
                        });
                })
                ->first();
    }
}