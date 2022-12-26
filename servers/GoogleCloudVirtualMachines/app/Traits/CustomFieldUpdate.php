<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\ProductCustomFields;

trait CustomFieldUpdate
{


    public function customFieldUpdate($name, $value=null){

        $prodModel = new ProductCustomFields($this->getWhmcsParamByKey('packageid'), $this->getWhmcsParamByKey('serviceid'));
        $prodModel->updateFieldValue($name, $value);
    }
}