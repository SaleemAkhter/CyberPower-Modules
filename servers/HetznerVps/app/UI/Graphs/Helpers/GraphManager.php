<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;

class GraphManager
{
    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * @param $start
     * Begin date
     * @param $end
     * End date
     * @param $step
     * Step in seconds
     * @return \LKDev\HetznerCloud\APIResponse
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function get($start, $end, $step){
        $serverId = $this->params['customfields']['serverID'];

        $api      = new Api($this->params);
        // time 86400 -> 60sec * 60min * 24h
        return $api->server($serverId)->metrics('cpu,disk,network', $start, $end, $step);
    }
//  public 'cpu' =>
//  public 'disk.0.iops.read' =>
//  public 'disk.0.iops.write' =>
//  public 'disk.0.bandwidth.read' =>
//  public 'disk.0.bandwidth.write' =>
//  public 'network.0.pps.in' =>
//  public 'network.0.pps.out' =>
//  public 'network.0.bandwidth.in' =>
//  public 'network.0.bandwidth.out' =>
}