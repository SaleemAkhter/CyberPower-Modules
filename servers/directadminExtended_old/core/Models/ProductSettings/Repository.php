<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings;

class Repository
{
    protected $modelInstance = null;

    public function __construct()
    {
        $this->modelInstance = new Model();
    }

    public function getProductSettings($pid = null)
    {
        $count = $this->modelInstance->where('pid', $pid)->count();
        if ($count === 0)
        {
            return [];
        }

        $data = $this->modelInstance->where('pid', $pid)->get()->toArray();

        $parsed = [];
        foreach ($data as $row => $values)
        {
            $parsed[$values['setting']] = ($values['setting'] === 'securityGroups' ? json_decode($values['value']) : $values['value']);
        }

        return $parsed;
    }

    public function updateProductSetting($pid = null, $setting = null, $value = '')
    {
        $count = $this->modelInstance->where('pid', $pid)->where('setting', $setting)->count();
        if ($count > 0)
        {
            $instance = $this->modelInstance->where('pid', $pid)->where('setting', $setting)->first();
            $instance->value = $value;
            return $instance->save();
        }

        return $this->modelInstance->fill(['pid' => $pid, 'setting' => $setting, 'value' => $value])->save();
    }

    public function removeAll($pid)
    {
        return $this->modelInstance->where('pid', $pid)->delete();
    }

}