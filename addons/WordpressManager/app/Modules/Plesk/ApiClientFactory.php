<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


class ApiClientFactory
{

    public static function fromParamsAsRoot(array $params){
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $client = new \PleskX\Api\Client($host );
        if( $params['accesshash']){
            $client->setSecretKey($params['accesshash']);
        }else{
            $client->setCredentials($params['serverusername'], $params['serverpassword']);
        }
        return $client;
    }

    public static function fromParamsAsUser(array $params){
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $client = new \PleskX\Api\Client($host );
        $client->setCredentials($params['username'], $params['password']);
        return $client;
    }
}