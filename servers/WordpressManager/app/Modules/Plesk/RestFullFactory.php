<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


use ModulesGarden\WordpressManager\App\Helper\Loger;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\RestFullApi;

class RestFullFactory
{
    /**
     * @param array $params
     * @return RestFullApi
     */
    public static function fromParamsAsRoot(array $params){
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $api = new RestFullApi($host);
        $api->setCredentials($params['serverusername'], $params['serverpassword']);
        if($params['debug']){
            $api->setLoger(new Loger('RestFullApi'));
        }
        return $api;
    }

}