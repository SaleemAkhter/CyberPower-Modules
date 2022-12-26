<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\App\Helpers\Models\Server\Constants;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Client;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\CustomField;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\CustomFieldValue;
use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ConfigOptions;

/**
 * Class CustomFields
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CustomFields
{

    public static function getAllClientWithVpsService()
    {
        $customFieldValueTable = (new CustomFieldValue())->getTable();
        $customFieldTable      = (new CustomField())->getTable();
        $hostingTable          = (new Hosting())->getTable();
        $clientsTable          = (new Client())->getTable();

        $res = CustomField::select("{$clientsTable}.id", "{$clientsTable}.firstname", "{$clientsTable}.lastname", "{$customFieldValueTable}.value"
            ,"{$hostingTable}.id as serviceId", "{$hostingTable}.domain"
            )
            ->join($customFieldValueTable, "{$customFieldTable}.id", '=', "{$customFieldValueTable}.fieldid")
            ->join($hostingTable, "{$hostingTable}.id", "=", "{$customFieldValueTable}.relid")
            ->join($clientsTable, "{$clientsTable}.id", "=", "{$hostingTable}.userid")
            ->where("{$customFieldTable}.fieldname", '=', ConfigOptions::OVH_SERVER_FIELD)
            ->where('type', '=', 'product')
            ->get()
            ->keyBy("value")
            ->toArray();

        return $res;
    }

    public static function getOvhServerFieldByService($serviceId)
    {
        $customFieldValueTable = (new CustomFieldValue())->getTable();
        $customFieldTable      = (new CustomField())->getTable();

        $newCustomField = new CustomFieldValue();

        $res = $newCustomField->select("$customFieldValueTable.*")
            ->join($customFieldTable, "$customFieldTable.id", '=', "$customFieldValueTable.fieldid")
            ->where("$customFieldValueTable.relid", '=', $serviceId)
            ->where("$customFieldTable.type", '=', 'product')
            ->where("$customFieldTable.fieldname", '=', 'serverName|Server Name')
            ->first()
            ;
        return $res;
    }
}