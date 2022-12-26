<?php


namespace ModulesGarden\Servers\VultrVps\App\Traits;


use ModulesGarden\Servers\VultrVps\App\Helpers\ProductCustomFields;

trait CustomFieldUpdate
{


    public function customFieldUpdate($name, $value = null)
    {

        $prodModel = new ProductCustomFields($this->getWhmcsParamByKey('packageid'), $this->getWhmcsParamByKey('serviceid'));
        $prodModel->updateFieldValue($name, $value);
    }
}