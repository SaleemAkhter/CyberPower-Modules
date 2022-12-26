<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;

class ThemeBlockForm extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->initIds('themeBlockForm');
        $this->setFormType('create');
        $this->setProvider(new ThemeBlockedProvider());
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
        $this->setInternalAlertMessage('confirmThemeBlock');
        foreach ($this->fields as &$field)
        {
            $field->setValue($action[$field->getId()]);
        }
    }
}
