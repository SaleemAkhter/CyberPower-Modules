<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 6, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Products\Buttons;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSwitchAjax;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Modules\Softaculous\CpanelProvider;
use \ModulesGarden\WordpressManager\App\Helper\LangException;

/**
 * Description of StatusSwitch
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class StatusSwitch extends ButtonSwitchAjax implements AdminArea
{

    use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
    protected $switchModel    = '\ModulesGarden\WordpressManager\App\Models\ProductSetting';
    protected $switchColumn   = 'enable';
    protected $switchOnValue  = 1;
    protected $switchOffValue = 0;
    protected $actionIdColumn = 'product_id';
    private $dataJsonResponse;

    public function returnAjaxData()
    {
        try
        {
            if ($this->getRequestValue('value', false) == "on")
            {
                $this->onEnable();
            }
        }
        catch (\Exception $ex)
        {
            if (preg_match('/scripts\snot\sfound/', $ex->getMessage()) || preg_match('/script\snot\sfound/', $ex->getMessage()))
            {
                return (new \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\DataJsonResponse())->setStatusError()->setMessageAndTranslate($ex->getMessage());
            }
        }
        return parent::returnAjaxData();
    }

    private function onEnable()
    {
        $productId = $this->getRequestValue('actionElementId', false);
        $product   = Product::findOrFail($productId);

        $params             = $product->getParams();
        $params['username'] = $params['serverusername'];
        $params['password'] = $params['serverpassword'];
        $module             = Wp::subModuleForProduct($product, $params);
        if ($module instanceof \ModulesGarden\WordpressManager\App\Modules\Cpanel)
        {
            $port     = $params['serversecure'] == 'on' ? 2087 : 2086;
            $host     = $params['serverip'] ? $params['serverip'] : $params['serverhostname'];
            $hostUrl  = 'https://' . $host . ":{$port}/cgi/softaculous/index.php";
            $provider = new CpanelProvider($hostUrl);
            if ($params['serveraccesshash'])
            {
                $provider->setAccessKey($params['serveraccesshash'])
                        ->authorizationWhm();
            }
            else
            {
                $provider->setUsername($params['serverusername'])
                        ->setPassword($params['serverpassword'])
                        ->authorizationBasic();
            }
            if ($product->setting && $product->setting->isDebugMode())
            {
                $provider->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpSoftaculous'));
            }
            $provider->setGet([
                'act' => 'softwares',
                'api' => 'json'
            ]);
            $provider->setPost([]);
            $response = $provider->sendRequest();
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
        }
        else
        {
            $app = $module->getInstallationScript();
        }
    }
}
