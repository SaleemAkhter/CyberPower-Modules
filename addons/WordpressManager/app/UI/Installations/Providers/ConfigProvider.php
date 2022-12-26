<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;
Use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ConfigProvider extends BaseDataProvider implements ClientArea
{

    public function create()
    {
        sl('lang')->addReplacementConstant("name", $this->formData['name']);
        if($this->formData['type']=="variable" && !preg_match('/^[A-Za-z]{1}[A-Za-z0-9\_]*$/', $this->formData['name'])){
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('Config :name: is invalid');
        }
        $installation = Installation::where('id', $this->request->get('wpid'))
                ->where('user_id', $this->request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $module->getConfig($installation)->set($this->formData['name'], $this->formData['value'], $this->formData['type']);
        Helper\infoLog(sprintf("Config '%s' has been created successfully, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id,  $this->request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Config :name: has been created successfully');
    }

    public function read()
    {
        $this->data = json_decode(base64_decode($this->actionElementId), true);
        $type =  $this->data['type'];
        $this->data['type']= (array)['value' =>   $type ];
        $this->data['defaultType']= $type;
        
        
    }

    public function update()
    {
        $installation = Installation::where('id', $this->request->get('wpid'))
                ->where('user_id', $this->request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $this->formData['type'] = $this->formData['defaultType'];
        if($this->formData['type']=="variable" && !preg_match('/^[A-Za-z]{1}[A-Za-z0-9\_]*$/', $this->formData['name'])){
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('Config :name: is invalid');
        }
        $module->getConfig($installation)->set($this->formData['name'], $this->formData['value'], $this->formData['type']);
        Helper\infoLog(sprintf("Config '%s' has been updated successfully, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id,  $this->request->getSession('uid')));
        sl('lang')->addReplacementConstant("name", $this->formData['name']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Config :name: has been updated successfully');
    }

    public function delete()
    {
        $installation = Installation::where('id', $this->request->get('wpid'))
                ->where('user_id', $this->request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $module->getConfig($installation)->delete($this->formData['name'], $this->formData['defaultType']);
        Helper\infoLog(sprintf("Config '%s' has been deleted successfully, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id,  $this->request->getSession('uid')));
        sl('lang')->addReplacementConstant("name", $this->formData['name']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Config :name: has been deleted successfully');
    }

}
