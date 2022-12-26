<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Client;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Pages\SnapshotList;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Pages\InstanceInfo;
/**
 * Description of Snapshots
 *
 * @author Kamil
 */
class Snapshot extends AbstractController {
    
    use WhmcsParams, ComputeTrait, ProductSetting;
    
    public function index(){
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {   
          return;   
        }
        
        $instanceHepler = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\InstanceHelper();
        
        if($this->productSetting()->snapshots != 'on'){
            return;
        }
        
        if(!$instanceHepler->instanceExists()){
            return Helper\view()->addElement(InstanceInfo::class);
        }
        
        
        return Helper\view()->addElement(SnapshotList::class);
    }
      
}
