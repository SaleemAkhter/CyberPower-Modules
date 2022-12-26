<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 31, 2018)
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

use ModulesGarden\WordpressManager\App\Modules\Plesk;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Modules\Softaculous\CpanelProvider;
use \ModulesGarden\WordpressManager\App\Helper\LangException;
/**
 * Description of InstallationScriptsSelect
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstallationScriptsSelect extends Select implements AdminArea
{
    protected $multiple = true;
    protected $productSetting;
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $selected = [];
        $options =[];
        $reposiotry               = new ProductSettingRepository;
        $this->productSetting     = $reposiotry->forProductId($this->request->get('id'));
        foreach($this->getInstallationScripts() as $id => $app){
            $options[] =[
                        'key'    => $id,
                        'value' =>  sprintf("#%s %s", $id, $app['name']),
                ];
            if(in_array($id, (array) array_keys($this->productSetting->getInstallationScripts()))){
                $selected[]= $id;
            }
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue($selected);
        $this->callBackFunction ='wpProductGeneralAjaxDone';
    }
    
    function getProductSetting()
    {
        return $this->productSetting;
    }

    function setProductSetting($productSetting)
    {
        $this->productSetting = $productSetting;
    }

    
    public function getInstallationScripts()
    {
        $this->loadRequestObj();
        $product             = Product::findOrFail($this->request->get('id'));
        $params              = $product->getParams();
        $params['username']  = $params['serverusername'];
        $params['password']  = $params['serverpassword'];
        $module              = Wp::subModuleForProduct($product, $params);
        if ($module instanceof \ModulesGarden\WordpressManager\App\Modules\Cpanel)
        {
            $port     =  $params['serversecure'] == 'on' ? 2087 : 2086;
            $host     =  $params['serverip'] ?  $params['serverip'] :  $params['serverhostname'];
            $hostUrl  = 'https://' . $host . ":{$port}/cgi/softaculous/index.php";
            $provider = new CpanelProvider($hostUrl);
            
            if (!$params['serveraccesshash'])
            {
                $provider->setUsername( $params['serverusername'])
                        ->setPassword( $params['serverpassword'])
                        ->authorizationBasic();
                if($this->productSetting->isDebugMode()){
                    $provider->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpSoftaculous'));
                }
                $provider->setGet([
                    'act' => 'softwares',
                    'api' => 'json'
                ]);
                $provider->setPost([]);
                $response = $provider->sendRequest();
            }
            else
            {
                $uapi = new \ModulesGarden\WordpressManager\App\Modules\Cpanel\Uapi();
                $uapi->setLogin($params)
                      ->createSession('whostmgrd');
                if($this->productSetting->isDebugMode()){
                      $uapi->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpSoftaculous'));
                }
                $response = $uapi->exec('/index.php', '/cgi/softaculous', [
                    'act' => 'softwares',
                    'api' => 'json'
                ]);
                $response = json_decode($uapi->getLastResponse(), true);
            }
            $data     = [];
            foreach ($response['iscripts'] as $id => $v)
            {
                $v['title'] = "#{$id} " . $v['name'];
                $v['id']    = $id;
                $data[$id]  = (array) $v;
            }
            if (!$data)
            {
                throw (new LangException("WordPress Installation scripts not found"))->translate();
            }
            return $data;
        }else if($module instanceof Plesk){
            $params['debug']= $this->productSetting->isDebugMode();
            $softa = Plesk\SoftaculousFactory::fromParamsAsRoot($params);
            $data     = [];
            $response =  $softa->getInstallationScripts();
            foreach ($response['iscripts'] as $id => $v)
            {
                $v['title'] = "#{$id} " . $v['name'];
                $v['id']    = $id;
                $data[$id]  = (array) $v;
            }
            if (!$data)
            {
                throw (new LangException("WordPress Installation scripts not found"))->translate();
            }
            return $data;
        }
        return $module->getInstallationScripts();
    }
}
