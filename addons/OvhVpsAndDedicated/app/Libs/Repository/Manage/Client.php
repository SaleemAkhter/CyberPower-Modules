<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Client as ClientModel;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class Client
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Client
{
    public static function searchClientQuery($search = '')
    {
        return ClientModel::select(
            DB::raw('id as `key`'),
            DB::raw('CONCAT("# ", id, " " , firstname, " ",lastname, " (", companyname, ") ", email) as value'))
            ->where("firstname", 'like', "%{$search}%")
            ->orWhere("lastname", 'like', "%{$search}%")
            ->orWhere("email", 'like', "%{$search}%")
            ->orWhere("companyname", 'like', "%{$search}%")
            ->orWhere("id", 'like', "%{$search}%");
    }
}