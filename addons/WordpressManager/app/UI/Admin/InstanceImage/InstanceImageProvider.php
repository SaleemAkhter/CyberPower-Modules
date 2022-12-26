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

namespace ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Http\Admin\BaseAdminController;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\App\Models\Installation;

/**
 * Description of InstanceImageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageProvider extends BaseModelDataProvider implements AdminArea
{
    use BaseAdminController;
    
    public function __construct()
    {
        parent::__construct(main\App\Models\InstanceImage::class);
    }

    public function read()
    {
        $this->data = $this->formData;
        $this->data = $this->formData;
        if($this->formData){
            return;
        }
        parent::read();
        $enable = $this->data['enable'];
        $this->data['enable']= $enable==1 ? "on":"off" ;
        $this->data['ftp_pass']= decrypt($this->data['ftp_pass']);
        $value = $this->data['protocol'];
        $this->data['protocol'] = ['value' => $value  ] ;
        $this->data['private'] =  $this->data['user_id'] ?   "on" : "off";
        $installationId = $this->data['installation_id'];
        if($installationId > 0){
            $this->data['custom'] = "off" ;
            $this->installation = Installation::where("id", $installationId)
                ->first();
            $this->availableValues['installation_id'] = [
                 $installationId => sprintf("#%s %s", $installationId, $this->installation->path)
            ];
        }else if($this->data['installation_id']===0){
            $this->data['custom'] = "on" ;
        }


    }
    
    

    public function create()
    {
        $this->formData['ftp_pass'] = encrypt($this->formData['ftp_pass']);
        $this->formData['soft'] =26;
        
        if($this->formData['custom']=="off"){
            $this->setInstallationId($this->formData['installation_id']);
            $this->formData['soft'] = $this->getInstallation()->getSoftId();
            $this->formData['domain'] = $this->getInstallation()->domain;
            $this->formData['installed_path'] = $this->getInstallation()->path;
            $this->formData['user_id'] = $this->formData['private'] == "on" ? $this->getInstallation()->user_id : 0;
        }else if($this->formData['custom']=="on"){
             $this->formData['installation_id'] =0;
        } 
        $enable = $this->formData['enable']=="on" ? 1:0;
        $this->formData['enable']= $enable ;
        $this->model->fill($this->formData)->save();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Instance image :name: has been created successfully');
    }
    
    public function update(){
        $this->formData['ftp_pass'] = encrypt($this->formData['ftp_pass']);
        $this->formData['soft'] =26;
        if($this->formData['custom']=="off"){
            $this->setInstallationId($this->formData['installation_id']);
            $this->formData['soft'] = $this->getInstallation()->getSoftId();
            $this->formData['user_id'] = $this->formData['private'] == "on" ? $this->getInstallation()->user_id : 0;
        }else if($this->formData['custom']=="on"){
             $this->formData['installation_id'] =0;
        } 
        $enable = $this->formData['enable']=="on" ? 1:0;
        $this->formData['enable'] = $enable ;
        parent::update();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Instance image :name: has updated successfully');
    }

    public function deleteMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected instance images have been deleted successfully');
    }
    
    public function enableMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->update(["enable" =>1]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected instance images have been enabled successfully');
    }
    
    public function disableMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->update(["enable" =>0]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected instance images have been disabled successfully');
    }
    
    public function delete()
    {
        parent::delete();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Instance image :name: has been deleted successfully');
    }
}
