<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;

use ModulesGarden\WordpressManager\App\Helper\Loger;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\PleskProvider;
use Illuminate\Database\Capsule\Manager as DB;

class ClientLoginFactory
{

    /**
     * @param array $params
     * @param Installation $installation
     * @return Api\WpCli
     */
    public static function fromParams(array $params){
        if(Hosting::ofUserAndServerTypePlesk($params['userid'])->count() <= 1){
            return $params['username'];
        }
        $externalId = DB::table('mod_pleskaccounts')->where('userid', $params['userid'])->value('panelexternalid');
        $api = ApiClientFactory::fromParamsAsRoot($params);
        return $api->customer()->get("external-id", $externalId)->login;
    }


}