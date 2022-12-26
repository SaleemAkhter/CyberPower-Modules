<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


use ModulesGarden\WordpressManager\App\Helper\Loger;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\PleskProvider;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\Softaculous;

class SoftaculousFactory
{

    /**
     * @param array $params
     * @return Softaculous
     */
    public static function fromParamsAsUser(array  $params){
        $sessionId =  ApiSessionFactory::fromParamsAsUser($params);
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $provider = new PleskProvider($host, $sessionId);
        if($params['debug']){
            $provider->setLoger(new Loger('WpSoftaculous'));
        }
        $provider->login();
        return new Softaculous($provider);
    }

    /**
     * @param array $params
     * @return Softaculous
     */
    public static function fromParamsAsRoot(array  $params){
        $sessionId =  ApiSessionFactory::fromParamsAsRoot($params);
        $host = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $provider = new PleskProvider($host, $sessionId);
        if($params['debug']){
            $provider->setLoger(new Loger('WpSoftaculous'));
        }
        $provider->login();
        return new Softaculous($provider);
    }

}