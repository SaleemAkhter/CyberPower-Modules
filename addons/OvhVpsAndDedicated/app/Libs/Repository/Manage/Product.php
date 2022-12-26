<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs\Product as ProductModel;

/**
 * Class Product
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Product
{
    public static function getToAssignQuery($serverid)
    {
        $query = ProductModel::select('tblproducts.id', 'tblproducts.name')
            ->leftJoin('tblservergroupsrel', function ($join) use ($serverid)
            {
                $join->on('tblservergroupsrel.groupid', '=', 'tblproducts.servergroup');
                $join->where("tblservergroupsrel.serverid", '=', $serverid);
            })
            ->join('tblservers', 'tblservers.id', '=', 'tblservergroupsrel.serverid')
            ->where('tblproducts.servertype', Server::OVH);

        return $query;
    }
}