<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs\Product;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ConfigOptions as ConfigOptionHelper;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
/**
 * Class MetaData
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigOptions extends AddonController
{
    public function execute($params = null)
    {


        if($this->getRequestValue('servergroup') == 0)
        {

            return [\ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server\EmptyProductPage::class, 'configOptions'];
        }

        $configOption = new ConfigOptionHelper($this->getRequestValue('id'));
        if ( 
            //server group 
            (($this->getRequestValue('servergroup') !== false && $this->getRequestValue('servergroup') != 0) || $this->getRequestValue('servergroup') === false)
            //params 
            && ($this->getRequestValue('action') === 'module-settings' || ($this->getRequestValue('loadData') && $this->getRequestValue('ajax') == '1')))
        {
            $this->initialize();
            return [\ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server\ProductPage::class, 'index'];
        }
    }

    private function initialize(){

        //init product
        $this->product = new Product();
        $this->product->id =  $this->getRequestValue('id');
        //init params
        Helper\sl("whmcsParams")->setParams($this->product->getParams());
    }
}
