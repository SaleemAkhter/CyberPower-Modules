<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;

use Illuminate\Database\Capsule\Manager as DB;

class ApiSessionFactory
{

    public static function fromParamsAsUser(array  $params){
        $client = ApiClientFactory::fromParamsAsRoot($params);
        return $client->server()->createSession(ClientLoginFactory::fromParams($params),  $_SERVER['SERVER_ADDR']);
    }

    public static function fromParamsAsRoot(array  $params){
        $client = ApiClientFactory::fromParamsAsRoot($params);
        return $client->server()->createSession($params['serverusername'],  $_SERVER['SERVER_ADDR']);
    }
}