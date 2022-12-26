<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Admin;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\ApiCredentials;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

/**
 * ServicePageIntegration controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ServicePageIntegration extends AbstractController
{
    use WhmcsParams;

    public function index()
    {
        if ($this->getWhmcsParamByKey('status') === 'Active')
        {
            return Helper\viewIntegrationAddon()
                ->addElement(StatusWidget::class);
        }
    }
}
