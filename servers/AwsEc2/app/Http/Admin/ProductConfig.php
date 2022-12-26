<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Admin;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\ApiCredentials;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\BlockedImages;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\CronInfo;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\Images;
use ModulesGarden\Servers\AwsEc2\Core\Http\AbstractController;
use ModulesGarden\Servers\AwsEc2\Core\Helper;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Product;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\UI\ConfigurableOption\OptionsWidget;

/**
 * ProductConfig page controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ProductConfig extends AbstractController
{
    
    /**
     *
     * @return \ModulesGarden\Servers\AwsEc2\Core\UI\ViewIntegrationAddon
     */
    public function index()
    {
        $view = Helper\viewIntegrationAddon()
            ->addElement(CronInfo::class)
            ->addElement(ConfigForm::class);

        $product = new Product($this->getRequestValue('id'));
        $productConfig = $product->getProductConfig();

        if (!$productConfig || !$productConfig['region'])
        {
            $view->addElement(BlockedImages::class);
        }
        else
        {
            $view->addElement(Images::class);
        }

        $view->addElement(OptionsWidget::class);

        return $view;
    }
}
