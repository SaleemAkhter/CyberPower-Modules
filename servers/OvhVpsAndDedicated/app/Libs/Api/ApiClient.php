<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

class ApiClient
{
    private $api;

    /**
     * @return Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param mixed $api
     * @return ApiClient
     */
    public function setApi($api)
    {
        $this->api = $api;
        return $this;
    }



}