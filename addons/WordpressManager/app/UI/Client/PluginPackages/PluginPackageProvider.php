<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Client\PluginPackages;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of PluginPackageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginPackageProvider extends BaseModelDataProvider implements ClientArea
{
    use BaseClientController;

    public function __construct()
    {
        parent::__construct(main\App\Models\PluginPackage::class);
    }
    
    public function read(){
        parent::read();
        $this->data['plugin_package_id'] = $this->data['id'];
    }
    
    public function install()
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallationId($this->getRequestValue('wpid'))
             ->setUserId($this->request->getSession('uid'))
             ->setPluginPackageId($this->formData['plugin_package_id']);
        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        foreach($this->getPluginPackage()->items as $item){
            /*@var $item main\App\Models\PluginPackageItem*/ 
            $this->subModule()->pluginInstall($this->getInstallation(), $item->slug);
        }
        main\Core\Helper\infoLog(sprintf("Plugin Package '%s' has been  installed Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Plugin package :name: has been installed successfully')
                                           ->setCallBackFunction('wpOnPluginInstalledAjaxDone');
    }
}
