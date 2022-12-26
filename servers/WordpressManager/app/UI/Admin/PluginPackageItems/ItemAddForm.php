<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 25, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackageItems;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\App\Http\Admin\BaseAdminController;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of PluginItemAddForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ItemAddForm extends BaseForm implements AdminArea
{
    use RequestObjectHandler;
    use BaseAdminController;
    
    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->initIds('itemAddForm');
        $this->setFormType('create');
        $this->setProvider(new ItemProvider());
        $this->initFields();
        $this->read();
    }
    
    protected function initFields()
    {
        $this->addField((new Fields\Hidden("name")));
        $this->addField((new Fields\Hidden("slug")));
        $this->addField((new Fields\Hidden("plugin_package_id")));
       
    }
    
    protected function readActionElement(){
        $data=[];
        $this->loadRequestObj();
        if($this->getRequestValue('actionElementId')){
            $data = json_decode(base64_decode($this->getRequestValue('actionElementId')), true);
            $data['plugin_package_id'] = $this->getRequestValue('id');
        }
        return $data;
    }
    
    private function read(){
        $this->loadRequestObj();
        if(! $this->getRequestValue('ajax'))
        {
            return; 
        }
        $action = $this->readActionElement();
        sl('lang')->addReplacementConstant('name', $action['name']);
        $this->setInternalAlertMessage('confirmPluginPackageItemAdd');
        $this->setInternalAlertMessageType(null);
        $details=['short_description'=> null];
        if($action['slug']){
            $this->setInstallationId((new ModuleSettings)->getTestInstallationId());
            $details = $this->subModule()->getPlugin($this->getInstallation())->detail($action['slug']);
        }
        $this->setConfirmMessage($details['short_description']);
        foreach ($this->fields as &$field)
        {
            $field->setValue($action[$field->getId()]);
        }
    }
}
