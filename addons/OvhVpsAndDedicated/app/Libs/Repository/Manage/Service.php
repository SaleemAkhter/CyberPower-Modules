<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class Service
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Service
{
    public static function getAllOvhByClient($clientId)
    {
        return Hosting::select(
            DB::raw("tblhosting.id as `key`"),
            DB::raw('CONCAT("#", tblhosting.id, " ", tblhosting.domain) as `value`'))
            ->join("tblproducts", "tblproducts.id", '=', "tblhosting.packageid")
            ->where("tblproducts.servertype", '=', Server::OVH)
            ->where("tblhosting.userid", '=', $clientId)
            ->orderBy('tblhosting.domain', 'asc')
            ->get();
    }
}