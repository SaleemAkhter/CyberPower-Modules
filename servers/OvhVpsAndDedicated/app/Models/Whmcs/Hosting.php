<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting as CoreHosting;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Server;

/**
 * Description of Product
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class Hosting extends CoreHosting
{
    public function servers()
    {
        return $this->hasOne(Server::class, "id", "server");
    }

    public function scopeActive($query)
    {
        return $query->where('domainstatus', 'Active');
    }

    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeOfServerType($query, $hostingId, $moduleName)
    {
        $h = "tblhosting";
        $p = "tblproducts";
        $query->select("{$h}.*");
        return $query->rightJoin($p, function ($join) use ($p)
        {
            $join->on('packageid', '=', "{$p}.id");
        })->where("{$h}.id", $hostingId)
            ->where("{$p}.servertype", $moduleName);
    }
}
