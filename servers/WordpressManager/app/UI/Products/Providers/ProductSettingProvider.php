<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Nov 17, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Products\Providers;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;



/**
 * Description of ProductSettingDataProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ProductSettingProvider extends BaseModelDataProvider implements AdminArea
{

    private $module;
    private $product;
    
    private $installationScripts;
    
    use RequestObjectHandler;

    public function __construct()
    {
        parent::__construct('\ModulesGarden\WordpressManager\App\Models\ProductSetting');
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
        if (is_null($this->product))
        {
             $this->product = Product::findOrFail($this->getRequestValue('actionElementId', false));
        }
        return $this->product;
    }

        public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    
        

    public function read()
    {
        if (!$this->actionElementId)
        {
            return;
        }
        
        $reposiotry               = new ProductSettingRepository;
        $model                    = $reposiotry->forProductId($this->actionElementId);
        $this->data               = $model->toArray();
        $this->data['product_id'] = $this->actionElementId;
        $is = $this->data['installationScripts'];
        $this->data['debugMode']  = $this->data['debugMode'] == "1" ? "on" : "off";
        
        $productId = $this->getRequestValue('actionElementId', false);
        if (!$productId)
        {
            return;
        }
        $this->data['installationScripts'] =[];
        $this->data['installationScripts']['value']= $is;
        if($this->getInstallationScripts()){
            foreach($this->getInstallationScripts() as $appId => $app){
                $this->data['installationScripts']['avalibleValues'][$appId] = $app['title'];
            }
        }
    }

    public function updateMass()
    {
        foreach ($this->getRequestValue('massActions') as $productId)
        {
            $reposiotry    = new ProductSettingRepository;
            $model         = $reposiotry->forProductId($productId);
            $model->enable = 1;
            $model->save();
        }
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('your changes has been saved successfully');
    }

    public function update()
    {

        $reposiotry            = new ProductSettingRepository;
        $model                 = $reposiotry->forProductId($this->formData['product_id']);
        $settings              = $model->getSettings();
        $settings['debugMode'] = $this->formData['debugMode'] == "on" ? 1 : 0;
        $settings['installationScripts']= $this->formData['installationScripts'];
        $model->setSettings($settings);
        $model->save();
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('your changes has been saved successfully');
    }

    public function create()
    {
        parent::create();
    }
    
    public function getInstallationScripts()
    {
        return $this->installationScripts;
    }

    public function setInstallationScripts($installationScripts)
    {
        $this->installationScripts = $installationScripts;
        return $this;
    }


}
