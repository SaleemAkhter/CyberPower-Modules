<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\UserData;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
/**
 * Description of UserDataSelect
 *
 * @author Kamil
 */
class UserDataSelect extends Select implements AdminArea
{
    
    public function prepareAjaxData()
    {

        //init setting
        $this->productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        //load options
        $this->setOptions();
        $this->setSelectedValue('0');
    }
    
    protected function setOptions(){
        
        $options = [];
        foreach(UserData::getFilesNames() as $key=>$value){
            $options[] = [
                'key' => $key,
                'value' => $value
            ];
        }

        $this->setAvailableValues($options);        
    }
    
    
    
}

