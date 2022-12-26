<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Traits\ParamsComponent;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

class ListAccounts
{
    use ParamsComponent;

    /**
     * @var Api
     */
    protected  $servers;


    public function __construct($params)
    {
        $this->setParams($params);
    }

    public function getAllServers()
    {
        $api      = new Api($this->params);
        $result = $api->servers()->all();

        $result = (array)$result;
        $accounts =[];
        //return $result;

        foreach ($result as $server)
        {
            $created = !empty($server->created) ? $server->created : '1970-01-01T00:00:00Z';
            $created = date('Y-m-d H:i:s', strtotime($created));
            $accounts[] = [
                'domain' => $server->name,
                'primaryip' => $server->publicNet->ipv4->ip,
                'status' => 'Active',
                'created' => $created,
                'uniqueIdentifier' => $server->name,
                'product' => '',
                'username' => '',
            ];
        }

        return $accounts;
    }

}
