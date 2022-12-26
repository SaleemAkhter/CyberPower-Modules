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

use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\BaseProvider;
use \ModulesGarden\WordpressManager\App\Models\PluginBlocked;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;

/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class PluginProvider extends BaseProvider implements ClientArea
{
    
        use BaseClientController;

    
    public function create()
    {
        $this->formData['hostingId']=$form['hostingId']=array_key_first($_SESSION['resellerloginas']);

        $hosting = Hosting::findOrFail($this->formData['hostingId']);
        $installationId=$_GET['wpid'];
        $model=Installation::OfId($installationId)->first();
        if($model){

            $postdata=[
                'custom_file'=>$_FILES['custom_file'],
                'insid'=>$model->relation_id
            ];

            return $this->uploadCustomPlugins($model,$postdata);
        }else{
            die("Unable to find");
        }

    }

    public function read()
    {
        
        if($this->actionElementId){
            $data  = json_decode(base64_decode($this->actionElementId), true);
            $this->data  = array_merge((array)$this->data,   $data);
        }
    }
    private function uploadCustomPlugins(Installation $instalation, $postdata)
    {
        $this->loadRequestObj();

        $this->setInstallation($instalation)
        ->setUserId($this->request->getSession('uid'));
        $this->setHostingId($this->formData['hostingId']);

        $installationresults=$names = [];
         // pluginUpload($installation,$data);
        foreach (\WHMCS\File\Upload::getUploadedFiles("custom_file") as $uploadedFile) {
            if (\WHMCS\File\Upload::isExtensionAllowed($uploadedFile->getCleanName())) {
                try {
                    $activate=$_POST['activate'];
                    $filename="file_".time()."_".$uploadedFile->getCleanName();
                    // $filename=$uploadedFile->getCleanName();
                    $destinationPath=ROOTDIR."/modules/addons/WordpressManager/uploads/".$filename;
                    if (!move_uploaded_file($_FILES['custom_file']['tmp_name'], $destinationPath)) {

                    }else{
                        global $CONFIG;
                        $fileurl=$CONFIG['SystemURL']."/modules/addons/WordpressManager/uploads/".$filename;
                        $response= $this->subModule()->setUsername($installation->username)->pluginInstall($this->getInstallation(), $fileurl,$activate);
                        if($response){
                            unlink($filename);
                        }
                    }

                } catch (\Exception $e) {
                    logActivity("Could not save file: " . $e->getMessage());

                }
            }else{
                return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Unable to install plugin')
                ->setCallBackFunction('wpOnPluginInstalledAjaxDone');
            }
        }
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Plugin has been installed successfully')
                ->setCallBackFunction('wpOnPluginInstalledAjaxDone');
    }
    public function detail($name){
    
        if($this->getInstallation()->username){
            $this->getModule()->setUsername($this->getInstallation()->username);
        }
        return $this->getModule()->getPlugin($this->getInstallation())->detail($name);
    }
    
    public function update()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $module->pluginUpdate($installation, $this->formData['name']);
        Helper\infoLog(sprintf("Plugin '%s' has been  updated, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id, $request->getSession('uid')));
       
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugin has been update successfully');
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

        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        
        if($installation->hosting->productSettings->isPluginBlocked() &&
            PluginBlocked::where("slug" ,$this->formData['slug'] )->where('product_id',$installation->hosting->packageid )->count()){
            
            sl('lang')->addReplacementConstant("name", UtilityHelper::htmlEntityDecode($this->formData['name']));
            return (new ResponseTemplates\HtmlDataJsonResponse())
                    ->setMessageAndTranslate('Plugin :name: cannot be installed. It has been blocked.')
                    ->setStatusError();
        }
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $module->pluginInstall($installation, $installValue);
        Helper\infoLog(sprintf("Plugin '%s' has been  installed Installation ID #%s, Client ID #%s, Hosting ID #%s",
                $this->formData['name'], 
                $installation->id, 
                $request->getSession('uid'),
                $installation->hosting_id));
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Plugin has been installed successfully')
                ->setCallBackFunction('wpOnPluginInstalledAjaxDone');
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
            $this->getModule()->getPlugin($this->getInstallation())->install($installValue);
            $entities[]=$installValue;
        }
        //Log
        Helper\infoLog(sprintf("Plugin '%s' has been installed, Installation ID #%s, Client ID #%s, Hosting ID #%s",
            implode(", ", (array) $entities),
            $this->getInstallation()->id,
            $this->getInstallation()->user_id,
            $this->getInstallation()->hosting_id));
        //Return Message
        sl('lang')->addReplacementConstant("names", implode(", ", (array) $entities));
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('The selected plugins have been installed successfully')
            ->setCallBackFunction('wpOnPluginInstalledAjaxDone');
    }
    
    public function activate()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $res          = $module->pluginActivate($installation, $this->formData['name']);
        Helper\infoLog(sprintf("Plugin '%s' has been  activated, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugin has been activate successfully');
    }

    public function deactivate()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $module->pluginDeactivate($installation, $this->formData['name']);
        Helper\infoLog(sprintf("Plugin '%s' has been deactivated, Installation ID #%s, Client ID #%s", $this->formData['name'], $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugin has been deactivate successfully');
    }

    public function activateMass()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $pugin)
        {
            $data  = json_decode(base64_decode($pugin), true);
            $entities[]=$data['name'];
            $module->pluginActivate($installation, $data['name']);
        }
        Helper\infoLog(sprintf("Plugins %s have been activated, Installation ID #%s, Client ID #%s", implode(", ", (array) $entities), $installation->id, $request->getSession('uid')
        ));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugins have been activated successfully');
    }

    public function deactivateMass()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $pugin)
        {
            $data  = json_decode(base64_decode($pugin), true);
            $entities[]=$data['name'];
            $module->pluginDeactivate($installation,  $data['name']);
        }
        Helper\infoLog(sprintf("Plugins %s have been deactivated, Installation ID #%s, Client ID #%s", implode(", ", (array) $entities), $installation->id, $request->getSession('uid')
        ));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugins have been deactivated successfully');
    }

    public function updateMass()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $entities = [];
        foreach ($this->getRequestValue('massActions') as $pugin)
        {
            $data  = json_decode(base64_decode($pugin), true);
            $entities[]=$data['name'];
            $module->pluginUpdate($installation, $data['name']);
        }
        Helper\infoLog(sprintf("Plugins %s have been updated, Installation ID #%s, Client ID #%s", implode(", ", (array) $entities), $installation->id, $request->getSession('uid')
        ));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugins have been updated successfully');
    }
    
    public function delete()
    {
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $this->request->get('wpid'))
                ->where('user_id', $this->request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $module->getPlugin($installation)->delete($this->formData['name']);
        Helper\infoLog(sprintf("Plugin '%s' has been  deleted, Installation ID #%s, Client ID #%s", $this->formData['title'], $installation->id, $this->request->getSession('uid')));
        sl('lang')->addReplacementConstant("name", $this->formData['title']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugin :name: has been deleted successfully');
    }
    
    public function deleteMass()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if($installation->username){
            $module ->setUsername($installation->username);
        }
        $entities = [];
        $plugin = $module->getPlugin($installation);
        foreach ($this->getRequestValue('massActions') as $pugin)
        {
            $data  = json_decode(base64_decode($pugin), true);
            $entities[]=$data['name'];
            $plugin->delete($data['name']);
        }
        Helper\infoLog(sprintf("Plugins %s have been deleted, Installation ID #%s, Client ID #%s", implode(", ", (array) $entities), $installation->id, $request->getSession('uid')
        ));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Plugins have been deleted successfully');
    }
}
