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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\EmailTemplate;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Models\Installation;



/**
 * Description of ProductSettingDataProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class SettingProvider extends BaseModelDataProvider implements AdminArea
{

    private $module;
    private $product;
    private $productSetting;
     /**
     * 
     * @deprecated since version 1.2.0
     */
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
        $this->data['debugMode']  = $this->data['debugMode'] == "1" ? "on" : "off";
        $this->data['installationScripts']['value']= $model->getSettings()['installationScripts'];
        $this->data['pluginBlocked']  = $this->data['pluginBlocked'] == "1" ? "on" : "off";
        $this->data['pluginBlockedDelete']  = $this->data['pluginBlockedDelete'] == "1" ? "on" : "off";
        $this->data['themeBlocked']  = $this->data['themeBlocked'] == "1" ? "on" : "off";
        $this->data['themeBlockedDelete']  = $this->data['themeBlockedDelete'] == "1" ? "on" : "off";
        $installationId = $model->getTestInstallation();
        if($installationId > 0){
            $this->installation = Installation::where("id", $installationId)
                                                ->first();
            $this->availableValues['testInstallation'] = [
                $installationId => sprintf("#%s %s", $installationId, $this->installation->path)
            ];
        }
        //autoInstall
        $this->availableValues["autoInstall"]=[ 0 => sl("lang")->tr("Disabled"), 'script' => sl("lang")->tr("Script"), 'instanceIamge' => sl("lang")->tr("Instance Iamge"), ];
        //autoInstallEmailTemplate
        $this->availableValues['autoInstallEmailTemplate']=[0=>""];
        foreach (EmailTemplate::where('type', "product")->where("custom",1)->pluck("name", "id")->all() as $key => $value)
        {
            $this->availableValues["autoInstallEmailTemplate"][$key] = $value;
        }

        $this->data['updateWpVersionNotifications']  = $this->data['updateWpVersionNotifications'] == "1" ? "on" : "off";

        foreach ($this->getUpdateWpTemplates() as $key => $value)
        {
            $this->availableValues['updateWpVersionNotificationTemplate'][$key] = $value;
        }
        //autoInstallSoftProto
        $this->availableValues["autoInstallSoftProto"] = [
            '1' => 'http://', '2' => 'http://www', '3' => ' https://', '4' => ' https://www'
        ] ;
        $this->data['deleteAutoInstall']  = $this->data['deleteAutoInstall'] == "0" ? "off":  "on" ;
    }

    private function getUpdateWpTemplates()
    {
        $records = [];

        $templates = collect(EmailTemplate::where(['type' => 'product', 'custom'=> 1])->get())->all();
        foreach($templates as $template)
        {
            $records[$template->id] = $template->name . ($template->language ? ' - ' . ucfirst($template->language) : '');

            /* Checking for duplications for other languages generated by WHMCS (column Custom is 0) */
            $duplicationsWithoutCustom = collect(EmailTemplate::where(['type' => 'product', 'custom' => 0, 'name' => $template->name])->get())->all();
            if(!empty($duplicationsWithoutCustom))
            {
                foreach($duplicationsWithoutCustom as $duplication)
                {
                    $records[$duplication->id] = $duplication->name . ($duplication->language ? ' - ' . ucfirst($duplication->language) : '');
                }
            }
        }

        return $records;
    }

    public function update()
    {
        $reposiotry            = new ProductSettingRepository;
        $this->productSetting  = $reposiotry->forProductId($this->formData['product_id']);
        $settings              = $this->productSetting->getSettings();
        
        $settings['debugMode'] = $this->formData['debugMode'] == "on" ? 1 : 0;
        $settings['installationScripts']= $this->installationScriptSave();
        $settings['pluginBlocked'] = $this->formData['pluginBlocked'] == "on" ? 1 : 0;
        $settings['pluginBlockedDelete'] = $this->formData['pluginBlockedDelete'] == "on" ? 1 : 0;
        $settings['themeBlocked'] = $this->formData['themeBlocked'] == "on" ? 1 : 0;
        $settings['themeBlockedDelete'] = $this->formData['themeBlockedDelete'] == "on" ? 1 : 0;
        $settings['pluginScanInteral'] = $this->formData['pluginScanInteral'];
        $settings['testInstallation']= $this->formData['testInstallation'];
        unset($settings['pluginPackages'],$settings['instanceImages'],$settings['customThemes'],$settings['customPlugins']);
        if($this->formData['pluginPackages']){
            $settings['pluginPackages']= $this->formData['pluginPackages'];
        }
        if($this->formData['instanceImages']){
            $settings['instanceImages']= $this->formData['instanceImages'];
        }
        if($this->formData['customThemes']){
            $settings['customThemes']= $this->formData['customThemes'];
        }
        if($this->formData['customPlugins']){
            $settings['customPlugins']= $this->formData['customPlugins'];
        }
        //autoInstall
        $settings['autoInstall'] = $this->formData['autoInstall'];
        //autoInstallScript
        $settings['autoInstallScript'] = $this->formData['autoInstallScript'];
        //autoInstallInstanceImage
        $settings['autoInstallInstanceImage'] = $this->formData['autoInstallInstanceImage'];
        //autoInstallEmailTemplate
        $settings['autoInstallEmailTemplate'] = $this->formData['autoInstallEmailTemplate'];
        //autoInstallPluginPackages
        $settings['autoInstallPluginPackages'] = $this->formData['autoInstallPluginPackages'];
        //autoInstallThemePackages
        $settings['autoInstallThemePackages'] = $this->formData['autoInstallThemePackages'];
        //autoInstallSoftProto
        $settings['autoInstallSoftProto'] = $this->formData['autoInstallSoftProto'];

        //InstallationsLimit
        $settings['installationsLimit'] = $this->formData['installationsLimit'];

        //UpdateWordpressVersionNotification
        $settings['updateWpVersionNotifications'] = $this->formData['updateWpVersionNotifications'] == "on" ? 1 : 0;
        $settings['updateWpVersionNotificationInterval'] = $this->formData['updateWpVersionNotificationInterval'];
        $settings['updateWpVersionNotificationTemplate'] = $this->formData['updateWpVersionNotificationTemplate'];
        $settings['defaultTheme'] = $this->formData['defaultTheme'];
        $settings['deleteAutoInstall'] = $this->formData['deleteAutoInstall'] == "on" ? 1 : 0;
        $this->productSetting->setSettings($settings);
        $this->productSetting->save();
        
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('your changes has been saved successfully')
                ->setCallBackFunction('wpProductSaveAjaxDone');
    }
    
    private function installationScriptSave(){
        $data=[];
        if($this->formData['installationScripts']){
            $installationScriptsSelect = new InstallationScriptsSelect();
            $installationScriptsSelect->setProductSetting($this->productSetting);
            foreach($installationScriptsSelect->getInstallationScripts() as $id => $app){
                if(!in_array($id, $this->formData['installationScripts']))
                    continue;
                $data[$id] =$app['name'];
            }
        }
        return $data;
    }

    public function create()
    {
        parent::create();
    }
    /**
     * 
     * @deprecated since version 1.2.0
     */
    public function getInstallationScripts()
    {
        return $this->installationScripts;
    }
     /**
     * 
     * @deprecated since version 1.2.0
     */
    public function setInstallationScripts($installationScripts)
    {
        $this->installationScripts = $installationScripts;
        return $this;
    }


}
