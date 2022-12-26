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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\ThemeBlocked;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\BaseProvider;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ThemeProvider extends BaseProvider implements ClientArea
{
    
    
    public function create()
    {
        
    }

    public function read()
    {
        
        if($this->actionElementId){
            $data  = json_decode(base64_decode($this->actionElementId), true);
            $this->data  = array_merge((array)$this->data,   $data);
        }
    }
    
    public function install()
    {
        if(!empty($this->formData['url']))
        {
            $installValue = $this->formData['url'];
        } else
        {
            $installValue = $this->formData['slug'];
        }

        $installation = $this->getInstallation();
        if($installation->hosting->productSettings->isThemeBlocked() &&
            ThemeBlocked::where("slug" ,$this->formData['slug'] )->where('product_id',$installation->hosting->packageid )->count()){

            sl('lang')->addReplacementConstant("name", UtilityHelper::htmlEntityDecode($this->formData['name']));
            return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Theme :name: cannot be installed. It has been blocked.')
                ->setStatusError();
        }

        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $this->getModule()->getTheme($this->getInstallation())->install($installValue);
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been installed, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
         //Return Message
        sl('lang')->addReplacementConstant("name", $this->formData['name']);
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Theme :name: has been installed successfully')
                ->setCallBackFunction('wpOnThemeInstalledAjaxDone');
    }
    
    public function activate()
    {
        //Activate
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $this->getModule()->getTheme($this->getInstallation())->activate($this->formData['name']);
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been activated, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("title", $this->formData['title']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Theme :title: has been activated successfully');
    }

    public function activateMass()
    {
         //Activate
        $entities = [];
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $this->getModule()->getTheme($this->getInstallation())->activate($data['name']);
            $entities[]=$data['name'];
        }
        //Log
        Helper\infoLog(sprintf("Themes '%s' have been activated, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                implode(", ", (array) $entities), 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
         //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been activated successfully');
    }
    
    public function delete()
    {
        //Delete
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $this->getModule()->getTheme($this->getInstallation())->delete($this->formData['name']);
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been deleted, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("title", $this->formData['title']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Theme :title: has been deleted successfully');
    }
    
    public function update()
    {
        //Update
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $this->getModule()->getTheme($this->getInstallation())->update($this->formData['name']);
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been updated, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("title", $this->formData['title']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Theme :title: has been updated successfully');
    }

    public function disable()
    {
        //Disable
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $this->getModule()->getTheme($this->getInstallation())->disable($this->formData['name']);

        //Log
        Helper\infoLog(sprintf("Theme '%s' has been disabled, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("title", $this->formData['title']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Theme :title: has been disabled successfully');
    }


    public function disableMass()
    {
         //Disable
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $this->getModule()->getTheme($this->getInstallation())->disable($data['name']);
            $entities[]=$data['name'];
        }
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been disabled, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                implode(", ", (array) $entities), 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
         //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been disabled successfully');

    }

    public function updateMass()
    {
         //Update
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $this->getModule()->getTheme($this->getInstallation())->update($data['name']);
            $entities[]=$data['name'];
        }
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been updated, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                implode(", ", (array) $entities), 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
         //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been updated successfully');
    }


    
    public function deleteMass()
    {
        //Delete
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $this->getModule()->getTheme($this->getInstallation())->delete($data['name']);
            $entities[]=$data['name'];
        }
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been deleted, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                implode(", ", (array) $entities), 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
         //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been deleted successfully');
        
    }

    
    public function installMass()
    {
        //Install
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            if(!empty($data['url']))
            {
                $installValue = $data['url'];
            } else
            {
                $installValue = $data['slug'];
            }
            $this->getModule()->getTheme($this->getInstallation())->install($installValue);
            $entities[]=$installValue;
        }
        //Log
        Helper\infoLog(sprintf("Theme '%s' has been installed, Installation ID #%s, Client ID #%s, Hosting ID #%s",
                implode(", ", (array) $entities), 
                $this->getInstallation()->id, 
                $this->getInstallation()->user_id,
                $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('The selected themes have been installed successfully')
                ->setCallBackFunction('wpOnThemeInstalledAjaxDone');
    }
}
