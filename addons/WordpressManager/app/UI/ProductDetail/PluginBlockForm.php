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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
/**
 * Description of PluginBlockForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockForm extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->initIds('pluginBlockForm');
        $this->setFormType('create');
        $this->setProvider(new PluginBlockedProvider());
        $this->initFields(); 
        $this->read(); 
    }
    
    protected function initFields()
    {
        $this->addField((new Fields\Hidden("name")));
        $this->addField((new Fields\Hidden("slug")));
        $this->addField((new Fields\Hidden("product_id")));
       
    }
    
    protected function readActionElement(){
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $data=[];
        if($request->get('actionElementId')){
            $data = json_decode(base64_decode($request->get('actionElementId')), true);
            $data['product_id'] =$request->get('id');
        }
        return $data;
    }
    
    private function read(){
        
        if(!\ModulesGarden\WordpressManager\Core\Helper\sl('request')->get('ajax'))
        {
            return; 
        }
        $action = $this->readActionElement();
        sl('lang')->addReplacementConstant('name', $action['name']);
        $this->setInternalAlertMessage('confirmPluginBlock');
        $details=['short_description'=> null];
        if($action['slug']){
            /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
            $request      = Helper\sl('request');
            $productSetting = (new ProductSettingRepository)->forProductId($request->get('id'));
            /* @var  $installation  Installation */
            $installation = Installation::where('id', $productSetting->getTestInstallation()) 
                                          ->firstOrFail();
            $module = Wp::subModule($installation->hosting);
            $details = $module->getPlugin($installation)->detail($action['slug']);
        }
        $this->setConfirmMessage($details['short_description']);
        foreach ($this->fields as &$field)
        {
            $field->setValue($action[$field->getId()]);
        }
    }
}
