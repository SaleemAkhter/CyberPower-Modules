<?php


namespace ModulesGarden\Servers\AwsEc2\App\Http\Client;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\Helper;
use ModulesGarden\Servers\AwsEc2\Core\Http\AbstractController;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Others\ModuleDescription;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Customs\Buttons\Breadcrumb;


class FirewallRules extends AbstractController
{
    use WhmcsParams,
    \ModulesGarden\Servers\AwsEc2\App\Traits\ClassNameComponent,
     \ModulesGarden\Servers\AwsEc2\App\Traits\BreadcrumbComponent;

    public function index()
    {
        $settingRepo = new Repository();
        $productInfo = $settingRepo->getProductSettings($this->getWhmcsParamByKey('packageid'));


        if (!($this->getWhmcsParamByKey('status') === 'Active'))
            return null;

        if($productInfo['enableFirewallConfig'] === 'on' && $this->securityGroupExists())
            return Helper\view()
                ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                ->addElement(ModuleDescription::class)
                ->addElement(\ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Pages\FirewallRulesPage::class);

        return null;
    }

    private function securityGroupExists()
    {
        $apiInstance = new ClientWrapper($this->getWhmcsParamByKey('pid'), $this->getWhmcsParamByKey('serviceid'));
        $sgroup = $apiInstance->getSecurityGroup('default');

        return !is_null($sgroup);
    }
}
