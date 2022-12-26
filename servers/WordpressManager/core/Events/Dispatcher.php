<?php

namespace ModulesGarden\WordpressManager\Core\Events;

use ModulesGarden\WordpressManager\App\Events\MyTestEvent;
use ModulesGarden\WordpressManager\Core\DependencyInjection;
use ModulesGarden\WordpressManager\Core\DependencyInjection\Container;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\Queue\DatabaseQueue;
use ModulesGarden\WordpressManager\Core\Helper\WhmcsVersionComparator;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast as ShouldBroadcast;

class Dispatcher extends \Illuminate\Events\Dispatcher
{
    public function __construct(Container $container)
    {
        $this->container    = $container;

        $this->initialize();
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false)
    {
        /* This function executes a different code, depending on the version of the container - WHMCS 8 has a much newer version */
        $version8OrHigher = (new WhmcsVersionComparator)->isWVersionHigherOrEqual('8.0.0');
        if($version8OrHigher)
        {
            return $this->fireForWhmcs8OrNewer($event, $payload, $halt);
        }

        /* ----------------------------- OLDER WHMCS -------------------------------------------- */
        if (is_object($event)) {
            list($payload, $event) = [[$event], get_class($event)];
        }

        $responses = [];

        if (! is_array($payload)) {
            $payload = [$payload];
        }

        $this->firing[] = $event;

        if (isset($payload[0]) && $payload[0] instanceof ShouldBroadcast) {
            $this->broadcastEvent($payload[0]);
        }

        foreach ($this->getListeners($event) as $listener) {
            $response = call_user_func_array($listener, $payload);
            if (! is_null($response) && $halt) {
                array_pop($this->firing);

                return $response;
            }

            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }

        array_pop($this->firing);

        return $halt ? null : $responses;
    }

    private function fireForWhmcs8OrNewer($event, $payload, $halt)
    {
        [$event, $payload] = $this->parseEventAndPayload(
            $event, $payload
        );

        if ($this->shouldBroadcast($payload)) {
            $this->broadcastEvent($payload[0]);
        }

        $responses = [];

        foreach ($this->getListeners($event) as $listener) {
            $response = $listener($event, $payload);
            if ($halt && ! is_null($response)) {
                return $response;
            }

            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }

        return $halt ? null : $responses;
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