<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;

class ClientSettingProvider extends BaseModelDataProvider implements AdminArea
{

    private $module;
    private $product;
    private $productSetting;
    private $productOptionsList;

    private $installationScripts;
    
    use RequestObjectHandler;

    public function __construct()
    {
        parent::__construct('\ModulesGarden\WordpressManager\App\Models\ProductSetting');

        $this->setAllOptions();
    }

    private function setAllOptions()
    {
        $this->productOptionsList = [
            'pageSpeedInsightsOption',
            'management-installation-details',
            'management-backups',
            'management-themes',
            'management-plugins',
            'management-plugin-packages',
            'management-config',
            'actions-control-panel',
            'actions-clear-cache',
            'actions-clone',
            'actions-update',
            'actions-change-domain',
            'actions-manage-auto-upgrade',
            'actions-staging',
            'actions-push-to-live',
            'actions-ssl',
            'actions-instance-image',
            'management-users',
            'actions-maintenance-mode',

        ];
    }
    
    public function getModule()
    {
        if (is_null($this->module))
        {
            if (in_array($this->getProduct()->servertype, ['directadmin', 'directadminExtended']))
            {
                $params             = $this->getProduct()->getParams();
                $params['username'] = $params['serverusername'];
                $params['password'] = $params['serverpassword'];
                $this->module       = Wp::subModuleForProduct($this->getProduct(), $params);
            }
            else
            {//cpanel
                $hosting      = Hosting::where('packageid', $this->getProduct()->id)
                                ->where('domainstatus', 'Active')
                                ->orderBy("id", 'desc')->firstOrFail();
                $this->module = Wp::subModule($hosting);
            }
        }
        return $this->module;
    }
    
    public function getProduct()
    {

        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function read()
    {
        $reposiotry               = new ProductSettingRepository;
        $model                    = $reposiotry->forProductId($this->getProduct()->id);
        $this->data               = $model->toArray();
        $this->data['product_id'] = $this->getProduct()->id;
        $productSettings = ProductSetting::where('product_id', $this->data['product_id'])->first();

        if(!$productSettings)
        {
            $this->firstTimeEnableOptions($this->data['product_id']);
            $this->data = $reposiotry->forProductId($this->getProduct()->id)->toArray();
        } 

        
        foreach($this->productOptionsList as $option)
        {
            $this->data[$option] = $this->setSwitcherDataValue($this->data[$option]);
        }
    }

    private function setSwitcherDataValue($data)
    {
        if(($data == '1' || empty($data)) && $data != 0)
        {
            return 'on';
        }

        return 'off';
    }


    public function update()
    {

        $repository            = new ProductSettingRepository;
        $this->productSetting  = $repository->forProductId($this->formData['product_id']);
        $settings              = $this->productSetting->getSettings();

        foreach($this->productOptionsList as $option)
        {
            $settings[$option] = $this->formData[$option] == "on" ? 1 : 0;
        }

        $this->productSetting->setSettings($settings);
        $this->productSetting->save();
        
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('your changes has been saved successfully')
                ->setCallBackFunction('wpProductSaveAjaxDone');
    }

    private function firstTimeEnableOptions($productId)
    {
        $repository            = new ProductSettingRepository;
        $this->productSetting  = $repository->forProductId($productId);
        $settings              = $this->productSetting->getSettings();
        foreach($this->productOptionsList as $option)
        {
            $settings[$option] = 1;
        }

        $this->productSetting->setSettings($settings);
        $this->productSetting->save();
    }
}
