<?php

namespace ModulesGarden\DirectAdminExtended\Core\Queue;

use ModulesGarden\DirectAdminExtended\Core\DependencyInjection;

class Queue
{
    /**
     * @var null
     */
    protected $callBefore   = null;

    /**
     * @var null
     */
    protected $callAfter    = null;

    /**
     *
     */
    public function process()
    {
        $queue  = DependencyInjection::get(DatabaseQueue::class);

        while($model = $queue->pop())
        {
            if($this->callBefore)
            {
                $callback   = $this->callBefore;
                $callback($model);
            }

            $job    = new Manager($model);
            $job->fire();

            if($this->callAfter)
            {
                $callback   = $this->callAfter;
                $callback($model);
            }
        }
    }

    /**
     * @param $callable
     * @throws \Exception
     */
    public function setCallBefore($callable)
    {
        if(!is_callable($callable))
        {
            throw new \Exception('Argument is not callable');
        }

        $this->callBefore   = $callable;
    }

    /**
     * @param $callable
     * @throws \Exception
     */
    public function setCallAfter($callable)
    {
        if(!is_callable($callable))
        {
            throw new \Exception('Argument is not callable');
        }

        $this->callAfter   = $callable;
    }
}