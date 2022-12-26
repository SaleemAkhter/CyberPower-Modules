<?php

namespace ModulesGarden\DirectAdminExtended\App\Http\Admin;

use ModulesGarden\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\DirectAdminExtended\Core\Helper;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Product;
use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;

class Features extends AbstractController
{

    public function index()
    {
        return Helper\view()
                        ->addElement(\ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages\FeaturesPage::class);
    }

    public function configuration()
    {
        try
        {
            ServiceLocator::call('lang')->addReplacementConstant('productName', Product::find($this->request->get('pid'))->name);
            return Helper\view()
                ->addElement(\ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages\ConfigurationPage::class);

        }
        catch (\Exception $ex)
        {
            $errorPageElement = new \ModulesGarden\DirectAdminExtended\App\UI\Admin\Custom\Pages\ErrorPage();
            $errorPageElement->setErrorMessage($ex->getMessage());

            return Helper\view()
                ->addElement($errorPageElement);
        }



    }
}
