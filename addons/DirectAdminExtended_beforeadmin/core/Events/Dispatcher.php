<?php

namespace ModulesGarden\DirectAdminExtended\Core\Events;

use ModulesGarden\DirectAdminExtended\App\Events\MyTestEvent;
use ModulesGarden\DirectAdminExtended\Core\DependencyInjection;
use ModulesGarden\DirectAdminExtended\Core\DependencyInjection\Container;
use ModulesGarden\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\DirectAdminExtended\Core\Queue\DatabaseQueue;

class Dispatcher extends \Illuminate\Events\Dispatcher
{
    public function __construct(Container $container)
    {
        $this->container    = $container;

        $this->initialize();
    }

    /**
     *
     */
    protected function initialize()
    {
        /**
         * Load available events
         */
        $path   = ModuleConstants::getFullPath('app', 'Config', 'events.php');
        $config = include($path);


        foreach($config as $event => $listeners)
        {
            foreach($listeners as $listener)
            {
                $this->listen($event, $listener);
            }
        }

        /**
         * Set queue resolver
         */
        $this->setQueueResolver(function(){
            return DependencyInjection::create(DatabaseQueue::class);
        });
    }

    /**
     * @param $class
     * @param $arguments
     */
    public function queue($class, $arguments)
    {
        $class  = implode('@', $this->parseClassCallable($class));

        $this->resolveQueue()->push("$class", serialize($arguments));
    }

    /**
     * @param $class
     * @param $method
     * @override
     * @return \Closure
     */
    protected function createQueuedHandlerCallable($class, $method)
    {
        return function () use ($class, $method)
        {
            $arguments = $this->cloneArgumentsForQueueing(func_get_args());

            if (method_exists($class, 'queue'))
            {
                $this->callQueueMethodOnHandler($class, $method, $arguments);
            }
            else
            {
                $this->resolveQueue()->push("{$class}@{$method}", serialize($arguments));
            }
        };
    }
}