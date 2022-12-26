<?php

namespace ModulesGarden\WordpressManager\Core\Queue;

use Illuminate\Contracts\Queue\Queue;
use ModulesGarden\WordpressManager\Core\Queue\Models\Job;

class DatabaseQueue
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
                            $query->where("retry_after", '<', date("Y-m-d H:i:s"));
                            $query->where('retry_count', '<', '30');
                        })
                        ->orWhere(function($query){
                            $query->where('status', '=', Job::STATUS_WAITING);
                            $query->where("retry_after", '<', date("Y-m-d H:i:s"));
                            $query->where('retry_count', '<', '30');
                        });
                })
                ->first();
    }

}