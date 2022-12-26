<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin;

class Config extends BaseDataProvider implements AdminArea
{
    protected $productId = null;
    private $productSettings = [];
    private $serverID = null;

    private function checkServer()
    {

        if ($this->getRequestValue('servergroup', 0) == 0)
        {
            throw new \Exception(ServiceLocator::call('lang')->absoluteTranslate('emptyServerGroup'));
        }

        $this->serverID = $this->getRequestValue('servergroup');
    }

    public function read()
    {
        $this->checkServer();
        $this->productId = $this->getRequestValue('id');
        $this->loadConfig();
        $this->loadProductConfig();
    }


    public function update()
    {
        $this->read();
        $formData = $this->getRequestValue('mgpci');

        $settingRepo = new Repository();
        $settingRepo->removeAll($this->productId);


        foreach ($formData as $name => $value)
        {
            if (isset($this->availableValues[$name]))
            {
                if (is_array($value))
                {
                    $corectValues = [];
                    foreach ($value as $option)
                    {
                        if (isset($this->availableValues[$name][$option]))
                        {
                            $corectValues[] = $option;
                        }
                    }
                    $value = json_encode($corectValues);
                }
                else if (!isset($this->availableValues[$name][$value]))
                {
                    continue;
                }
            }

            $settingRepo = new Repository();
            $settingRepo->updateProductSetting($this->productId, $name, $value);
        }
    }

    private function loadConfig()
    {
        $this->availableValues['reseller_ip']  = [
            'shared'         => 'shared',
            'sharedreseller' => 'sharedreseller',
            'assign'         => 'assign'
        ];

        $serverParams = ServerParams::getFirstByGroupId($this->serverID);
        $serverParams['serverpassword'] = html_entity_decode($serverParams['serverpassword'], ENT_QUOTES);

        if(is_null($serverParams)){
            throw new \Exception(ServiceLocator::call('lang')->absoluteTranslate('selectServerAndSave'));
        }
        $directAdmin  = new DirectAdmin($serverParams);

        $productDetails = \ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Product::where('id', $this->productId)->first();

        if($productDetails->type == 'reselleraccount')
        {
            $packages     = $directAdmin->package->getResellersPackages();
        }else{
            $packages     = $directAdmin->package->getUserPackages();
        }

        $packagesArray = ['custom'  => '-- Custom --'];
        foreach($packages->response as $package)
        {
            $packagesArray[$package->getName()] = $package->getName();
        }

        $this->availableValues['package']     = $packagesArray;
    }



    protected function loadProductConfig()
    {
        $settingRepo = new Repository();
        $this->data = $settingRepo->getProductSettings($this->productId);

    }

}
