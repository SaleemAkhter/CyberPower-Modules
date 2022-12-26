<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\AccountDetails;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\AdminHomePage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\ResellerHomePage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\ResellerHomePageStats;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\StartAccount;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\EndAccount;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\UsageGraph;

class Home extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        WhmcsParams;

    public function index()
    {

        // debug($_SESSION);die();
        // unset($_SESSION['resellerloginas']);
        // unset($_SESSION['adminloginas']);
        // unset($_SESSION['adminloginasrole']);

        if($this->getWhmcsParamByKey('producttype')  == "server" && !Helper\isAdminLevel())
        {
            return Helper\view()->addElement((new StartAccount("ReselletAccountSectionLeft"))->setClass(['col-lg-6','reseller']))->addElement(AccountDetails::class)->addElement(UsageGraph::class)->addElement(new EndAccount("ReselletAccountSectionRight"))->addElement(AdminHomePage::class);
        }elseif(($this->getWhmcsParamByKey('producttype')  == "reselleraccount" && !Helper\isResellerLevel()) || ($this->getWhmcsParamByKey('producttype')  == "server" && Helper\isAdminLoggedInAsReseller())) {
            return Helper\view()->addElement((new StartAccount("ReselletAccountSectionLeft"))->setClass(['col-lg-6','reseller']))->addElement(AccountDetails::class)->addElement(UsageGraph::class)->addElement(new EndAccount("ReselletAccountSectionRight"))->addElement(ResellerHomePage::class);
        }

        $homeView = Helper\view();
        $this->loadFeaturesSettingsList();
        if($this->featuresSettingsList->usage_bandwidth === "on" ||
           $this->featuresSettingsList->usage_email     === "on" ||
           $this->featuresSettingsList->usage_ftp       === "on" ||
           $this->featuresSettingsList->usage_database  === "on" ||
           $this->featuresSettingsList->usage_disk      === "on")
        {
            if(Helper\isResellerLevel() || Helper\isAdminLevel()){
                $additonalclass='loggedInAs';
            }else{
                $additonalclass="single";
            }
            $homeView
            ->addElement((new StartAccount("UserAccountSectionLeft"))->setClass(['col-lg-6',$additonalclass]))
            ->addElement(AccountDetails::class)
            ->addElement(UsageGraph::class)
            ->addElement(new EndAccount("UserAccountSectionRight"));
        }

        $homeView->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages\HomePage::class);
        return $homeView;
    }
}
