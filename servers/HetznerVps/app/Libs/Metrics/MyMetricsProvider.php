<?php


namespace ModulesGarden\Servers\HetznerVps\App\Libs\Metrics;

use WHMCS\UsageBilling\Contracts\Metrics\MetricInterface;
use WHMCS\UsageBilling\Contracts\Metrics\ProviderInterface;
use WHMCS\UsageBilling\Metrics\Metric;
use WHMCS\UsageBilling\Metrics\Units\WholeNumber;
use WHMCS\UsageBilling\Metrics\Usage;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api\Constants;
use WHMCS\Service\Service;


class MyMetricsProvider implements ProviderInterface
{
    private $moduleParams = [];
    public function __construct($moduleParams)
    {
        $this->moduleParams = $moduleParams;
    }

    public function metrics()
    {
        return [
            new Metric(
                'cores',
                'Cores',
                MetricInterface::TYPE_SNAPSHOT
            ),
            new Metric(
                'memory',
                'Memory',
                MetricInterface::TYPE_SNAPSHOT
            ),
            new Metric(
                'disk',
                'Disk',
                MetricInterface::TYPE_SNAPSHOT
            )
        ];
    }

    public function usage()
    {
        $services = $this->apiCall(Constants::ALL);

        $usage = [];
        foreach ($services as $service)
        {
            $serverData = [
                'cores' => $service->serverType->cores,
                'memory' => $service->serverType->memory,
                'disk' => $service->serverType->disk
            ];

            $usage[$service->name] = $this->wrapUserData($serverData);
        }
        return $usage;
    }

    public function tenantUsage($tenant)
    {
        $service    = Service::where('domain', $tenant)->where('server', $this->moduleParams['serverid'])->first();
        $serverid = $service->serviceProperties->get('serverID');
        $serverValues = $this->apiCall(Constants::GET, $serverid);

        $userData = [
            'cores' => $serverValues->serverType->cores,
            'memory' => $serverValues->serverType->memory,
            'disk' => $serverValues->serverType->disk
        ];

        return $this->wrapUserData($userData);
    }

    private function wrapUserData($data)
    {
        $wrapped = [];
        foreach ($this->metrics() as $metric) {
            $key = $metric->systemName();
            if ($data[$key]) {
                $value = $data[$key];
                $metric = $metric->withUsage(
                    new Usage($value)
                );
            }

            $wrapped[] = $metric;
        }

        return $wrapped;
    }

    private function apiCall($function, $value=null)
    {
        $api      = new Api($this->moduleParams);
        return $api->servers()->$function($value);
    }

}