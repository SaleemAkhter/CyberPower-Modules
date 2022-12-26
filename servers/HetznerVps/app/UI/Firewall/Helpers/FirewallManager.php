<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;

class FirewallManager
{
    protected $params = [];
    private $serverId;

    public function __construct($params)
    {
        $this->params = $params;
        $this->serverId = $this->params['customfields']['serverID'];
    }

    public function read()
    {
        $api = new Api($this->params);
        return $api->firewalls()->get($this->params['customfields']['serverID']);
    }

    public function get()
    {
        $api = new Api($this->params);
//        return $api->firewalls()->get();
        $server = $api->servers()->get($this->serverId);
        $firewalls = [];
        foreach ($server->publicNet->firewalls as $item){
            if($item->status == 'applied'){
                $firewall = $api->firewalls()->get($item->id);
                foreach ($firewall->rules as $rule) {
                    $rule->ip = empty($rule->sourceIPs) ? $rule->destinationIPs : $rule->sourceIPs;
                }
                $firewalls[$server->name] = $firewall;
            }
        }
        return $firewalls;
    }

    public function getFirewall($firewallId)
    {
        $api = new Api($this->params);
        return $api->firewalls()->getById($firewallId);
    }

    public function getFirewallRules($firewallId)
    {
        $api = new Api($this->params);
        $api->firewalls()->getById($firewallId);
        $rules = [];
        $firewall = $api->firewalls()->getById($firewallId);
        foreach ($firewall->rules as $item) {
            $item->ip = empty($item->sourceIPs) ? $item->destinationIPs : $item->sourceIPs;
            $item->firewallId = $firewallId;
            $item->firewallName = $firewall->name;
            $rules[] = $item;
        }
        return $rules;
    }

    public function getAll()
    {
        $api = new Api($this->params);
        return $api->firewalls()->all();
    }

    public function listAll()
    {
        $api = new Api($this->params);
        return $api->firewalls()->list();
    }
}