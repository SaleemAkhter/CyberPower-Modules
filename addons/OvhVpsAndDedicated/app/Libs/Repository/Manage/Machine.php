<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\App\Models\Machine as MachineDbModel;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Machine as MachinePageHelper;
use ModulesGarden\OvhVpsAndDedicated\App\Models\MachineReuseProducts;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Machine
{
    CONST REUSE = 'reuse';
    CONST SERVER = 'server';

    public static function getAllGroupedByVpsName()
    {
        $machinesSettings = MachineDbModel::get();
        $out = [];
        foreach ($machinesSettings as $setting)
        {
            $out[$setting->name][$setting->setting] = $setting->value;
        }

        return $out;
    }

    public static function getVpsByName($name)
    {
        return MachineDbModel::where('name', '=', $name)->first();
    }

    public static function createOrUpdateSetting($vpsName, $setting, $value)
    {
        $res = MachineDbModel::where('name', $vpsName)->where('setting', $setting);
        if($res->first())
        {
            $res->update(['value' => $value]);
        }
        else
        {
            self::create($vpsName, $setting, $value);
        }

        if($setting == SELF::REUSE && $value == 'on')
        {
            self::assignProductsToVps($vpsName);
        }
    }

    public static function assignProductsToVps($vpsName)
    {
        foreach (MachinePageHelper::getProducts($vpsName) as $id => $name)
        {
            $vpsReuseProduct            = new MachineReuseProducts();
            $vpsReuseProduct->name      = $vpsName;
            $vpsReuseProduct->productId = $id;
            $vpsReuseProduct->save();
        }
    }

    public static function create($vpsName, $setting, $value)
    {
        $model = new MachineDbModel();
        $model->name = $vpsName;
        $model->setting = $setting;
        $model->value = $value;
        $model->save();
    }
}