<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


use ModulesGarden\WordpressManager\App\Modules\Plesk\ApiClientFactory;
use Illuminate\Database\Capsule\Manager as DB;

class GetDomainListFactory
{

    public static function fromParams($params)
    {
        $externalId = DB::table('mod_pleskaccounts')->where('userid', $params['userid'])->value('panelexternalid');
        $api = ApiClientFactory::fromParamsAsRoot($params);
        return $api->request("<packet>
                                  <customer>
                                    <get-domain-list>
                                        <filter> <external-id>{$externalId}</external-id> </filter>
                                   </get-domain-list>
                                   </customer>
                               </packet>");

    }
}