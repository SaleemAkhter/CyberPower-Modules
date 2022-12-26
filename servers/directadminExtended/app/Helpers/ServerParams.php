<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Server;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\ServersRelations;
use WHMCS\Database\Capsule as DB;

class ServerParams
{
    public static function getFirstByGroupId($groupId)
    {
        $server = Server::join('tblservergroupsrel', 'tblservergroupsrel.serverid', '=', 'tblservers.id')
            ->where('tblservergroupsrel.groupid', $groupId)->first();

        if($server->exists)
        {
            return [
                'serverid'         => $server-->id,
                'serverip'         => $server->ipaddress,
                'serverhostname'   => $server->hostname,
                'serversecure'     => $server->secure,
                'serverport'       => $server->port,
                'serverusername'   => $server->username,
                'serverpassword'   => decrypt($server->password) ? decrypt($server->password) : $server->password,
                'serveraccesshash' => $server->accesshash,
                'serverport'       => $server->port
            ];
        }
    }
    public static function getServerParamsById($serverId)
    {
        $server = Server::where('id', $serverId)->first();
        if ($server)
        {
            return [
                'serverid'         => $server->id,
                'serverip'         => $server->ipaddress,
                'serverhostname'   => $server->hostname,
                'serversecure'     => $server->secure,
                'serverusername'   => $server->username,
                'serverport'       => $server->port,
                'serverpassword'   => decrypt($server->password) ? decrypt($server->password) : $server->password,
                'serveraccesshash' => $server->accesshash,
                'serverport'       => $server->port
            ];
        }
    }

    public static function getServerParamsByHostingId($hostingId)
    {
        $hosting = Hosting::factory($hostingId);
        $server  = $hosting->getServer();

        return [
            'serverid'       => $server->id,
            'serverusername' => $server->username,
            'serverhostname' => $server->hostname,
            'serverip'       => $server->ipaddress,
            'serversecure'   => $server->secure,
            'serverport'     => $server->port,
            'accesshash'     => $server->accesshash,
            'serverpassword' => decrypt($server->password) ? decrypt($server->password) : $server->password,
            'domain'         => $hosting->domain
        ];
    }

    public static function getServerParamsByProductId($productId)
    {
        $serverId = DB::table('tblproducts')
                        ->join('tblservergroups', 'tblproducts.servergroup', '=', 'tblservergroups.id')
                        ->join('tblservergroupsrel', 'tblservergroups.id', '=', 'tblservergroupsrel.groupid')
                        ->join('tblservers', 'tblservergroupsrel.serverid', '=', 'tblservers.id')
                        ->where('tblproducts.id', '=', $productId)
                        ->first(['tblservers.id'])
                        ->id;

        return self::getServerParamsById($serverId);
    }

    public static function getServerParamsForSSOByHostingId($hostingId)
    {
        $hostingParams               = Hosting::factory($hostingId);
        $productId                   = Hosting::factory()->getProductIdByHostingId($hostingId);
        $productType                 = Product::where('id', $productId)->first()->type;
        $serverParams                = self::getServerParamsByHostingId($hostingId);
        $serverParams['username']    = $hostingParams['username'];
        $serverParams['producttype'] = $productType;

        return $serverParams;
    }
}
