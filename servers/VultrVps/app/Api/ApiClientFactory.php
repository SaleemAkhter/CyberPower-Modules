<?php

namespace ModulesGarden\Servers\VultrVps\App\Api;

use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;

class ApiClientFactory
{

    use WhmcsParams;

    /**
     * @param array $params
     * @return ApiClient
     */
    public static function formParams(array $params)
    {

        if (!$params['serverip'] && !$params['serverhostname'])
        {
            throw new \InvalidArgumentException("Server Hostname or  IP Address  is empty.");
        }
        if (!$params['serverpassword'])
        {
            throw new \InvalidArgumentException("Server Password is empty.");
        }
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        return new ApiClient($host, $params['serverpassword']);

    }

    public function fromWhmcsParams()
    {
        if (!$this->getWhmcsParamByKey('serverip') && !$this->getWhmcsParamByKey('serverhostname'))
        {
            throw new \InvalidArgumentException("Server Hostname or  IP Address  is empty.");
        }
        if (!$this->getWhmcsParamByKey('serverpassword'))
        {
            throw new \InvalidArgumentException("Server Password is empty.");
        }
        $host = $this->getWhmcsParamByKey('serverhostname') ? $this->getWhmcsParamByKey('serverhostname') : $this->getWhmcsParamByKey('serverip');
        return new ApiClient($host, $this->getWhmcsParamByKey('serverpassword'));
    }
}