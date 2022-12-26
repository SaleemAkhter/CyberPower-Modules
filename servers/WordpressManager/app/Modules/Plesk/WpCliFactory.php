<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;

use ModulesGarden\WordpressManager\App\Helper\Loger;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\PleskProvider;

class WpCliFactory
{

    /**
     * @param array $params
     * @param Installation $installation
     * @return Api\WpCli
     */
    public static function fromParamsAndInstallation(array $params, Installation $installation){
        $restFull = RestFullFactory::fromParamsAsRoot($params);
        if($params['debug']){
            $restFull->setLoger(new Loger('WpmCli'));
        }
        return new WpCli($restFull->cli()->extension(), $installation->path, $params['username']);

    }
}