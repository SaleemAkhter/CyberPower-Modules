<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\AccountDetails;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\ResellerHomePage;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;

class Home extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        WhmcsParams;

    public function index()
    {
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount")
        {
            return Helper\view()->addElement(ResellerHomePage::class);
        }
        $homeView = Helper\view()->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\HomePage::class);

        $this->loadFeaturesSettingsList();


        if($this->featuresSettingsList->usage_bandwidth === "on" ||
           $this->featuresSettingsList->usage_email     === "on" ||
           $this->featuresSettingsList->usage_ftp       === "on" ||
           $this->featuresSettingsList->usage_database  === "on" ||
           $this->featuresSettingsList->usage_disk      === "on")
        {

            $homeView->addElement(AccountDetails::class);
        }

        return $homeView;
    }
}
