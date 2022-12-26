<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs\Product;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

/**
 * Class ConfigOptions
 *
 * @author <slawomir@modulesgarden.com>
 */
class ConfigOptions extends AddonController
{
    public function execute($params = null)
    {
        if (($this->getRequestValue('action') === 'module-settings' || ($this->getRequestValue('loadData') && $this->getRequestValue('ajax') == '1')))
        {
            $this->initialize();
            return [\ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Admin\ProductConfig::class, 'index'];
        }
        else if ($this->getRequestValue('action') === 'save')
        {
            $form = new ConfigForm();
            $form->runInitContentProcess();
            $form->returnAjaxData();
        }
    }

    private function initialize(){

        //init product
        $this->product = new Product();
        $this->product->id =  $this->getRequestValue('id');
        //init params
        sl("whmcsParams")->setParams($this->product->getParams());
        //init api
        sl('ApiClient')->setGoogleClient((new GoogleClientFactory())->fromParams())
            ->setProject((new ProjectFactory())->fromParams());
    }
}
