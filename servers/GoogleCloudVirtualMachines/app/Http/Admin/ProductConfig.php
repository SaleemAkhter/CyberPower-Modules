<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Admin;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs\Product;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\ApiCredentials;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\BlockedImages;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\CronInfo;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages\Images;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\OptionsWidget;

/**
 * ProductConfig page controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ProductConfig extends AbstractController
{
    
    /**
     *
     * @return \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ViewIntegrationAddon
     */
    public function index()
    {

        $view = Helper\viewIntegrationAddon()
            ->addElement(ConfigForm::class)
            ->addElement(OptionsWidget::class);

        return $view;
    }
}
