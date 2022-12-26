<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Product;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers\FeaturesHelper;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers\Applications;
use function ModulesGarden\DirectAdminExtended\Core\Helper\sl;

class ConfigurationProvider extends BaseDataProvider
{

    public function read()
    {
        $productId = $this->getRequestValue('pid');
        if ($this->getRequestValue('additional') == 'copyConfiguration' && $this->getRequestValue('fromId'))
        {
            $productId = $this->getRequestValue('fromId');
        }

        $settingsModel = new FunctionsSettings();
        $productModel  = new Product();
        $products      = $productModel->join('tblproductgroups', 'tblproductgroups.id', '=', 'tblproducts.gid')
                ->where('tblproducts.servertype', '=', 'directadminExtended')
                ->get(['tblproducts.id as pid', 'tblproducts.name as name', 'tblproductgroups.name as group'])
                ->toArray();

        $this->data['copyFrom']          = [];
        $this->data['copyFrom']['value'] = [];

        foreach ($products as $product)
        {
            if ($product['pid'] == $productId)
            {
                continue;
            }
            $this->availableValues['copyFrom'][$product['pid']] = $product['group'] . ' - ' . $product['name'];
        }

        $settings = $settingsModel->where('product_id', '=', $productId)->get()->toArray();
        foreach ($settings[0] as $setting => $value)
        {
            $this->data[$setting] = $value;
        }

        if ($this->data['apps_order_assign'] == 1)
        {
            $this->data['apps_order_assign'] = 'on';
        }

        $featuresArray = FeaturesHelper::getFeaturesNames();
        foreach ($featuresArray as $feat => $features)
        {
            $mainSwitch = true;
            foreach ($features as $f)
            {
                if ($this->data[$f] != 'on')
                {
                    $mainSwitch = false;
                }
            }
            if ($mainSwitch)
            {
                $name              = FeaturesHelper::getName($feat);
                $this->data[$name] = 'on';
            }
        }

        $currentAutoinstaller                    = $this->data['apps_installer_type'];
        $this->data['apps_installer_type']       = $currentAutoinstaller;
        $this->availableValues['apps_installer_type'] = [
            2 => 'Installatron',
            1  => 'Softaculous',
        ];


        $currentWebmailType                     = $this->data['webmail_type'];
        $this->data['webmail_type']             = [];
        $this->data['webmail_type']             = $currentWebmailType;
        $this->availableValues['webmail_type']  = [
            1  => 'Roundcube',
        ];
    }

    public function create()
    {
        
    }

    public function update()
    {
        $model = new FunctionsSettings();
        foreach ($this->formData as $key => $val)
        {
            if ($key == 'copyFrom' || $key == 'product_id')
            {
                continue;
            }

            $query = $model->newQuery();
            try
            {
                if ($val == 'off')
                {
                    $val = '';
                }
                if ($key == 'apps_order_assign')
                {
                    switch ($val)
                    {
                        case 'on':
                            $val = 1;
                            break;
                        case '':
                            $val = 0;
                            break;
                    }
                }

                if (!$query->where('product_id', '=', sl('request')->get('pid'))->first())
                {
                    $query->insert(['product_id' => sl('request')->get('pid'), $key => $val]);
                }
                else
                {
                    $query->where('product_id', '=', sl('request')->get('pid'))->update([$key => $val]);
                }
            }
            catch (\Exception $ex)
            {
                continue;
            }
        }
    }

    public function delete()
    {
        
    }
}
