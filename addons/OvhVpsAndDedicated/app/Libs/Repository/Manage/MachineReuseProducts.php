<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\App\Models\MachineReuseProducts as MachineReuseProductsDbModel;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class MachineReuseProducts
{
    public static function getAll()
    {
        return MachineReuseProductsDbModel::get();
    }

    public static function getAllByName($name)
    {
        return MachineReuseProductsDbModel::where('name', '=', $name)->get();
    }

    public static function removeForVps($vpsName)
    {
        MachineReuseProductsDbModel::where('name', '=', $vpsName)->delete();
    }
}