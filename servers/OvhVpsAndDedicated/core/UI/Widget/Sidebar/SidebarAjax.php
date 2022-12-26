<?php

/* * ********************************************************************
 * OvhVpsAndDedicated product developed. (Nov 19, 2018)
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

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Sidebar;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

/**
 * Description of SidebarAjax
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgardne.com>
 */
class SidebarAjax extends Sidebar implements \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
    
    protected $id   = 'sidebarAjax';
    protected $name = 'sidebarAjax';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-ajax-sidebar';        
    
    protected $ajaxMenuElements = [];
    
    /** 
     * overwrite this function, use add function to add ajax elements
     */
    public function prepareAjaxData()
    {
        
    }        
    
    /** 
     * do not overwrite this function
     * @return type \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\RawDataJsonResponse
     */
    public function returnAjaxData()
    {
        $this->prepareAjaxData();
        
        $returnData = $this->parseProvidedData();

        return (new ResponseTemplates\RawDataJsonResponse($returnData))->setCallBackFunction($this->callBackFunction);
    }
    
    protected function parseProvidedData()
    {
        $this->loadLang();
        
        $data = [];
        foreach ($this->ajaxMenuElements as $mItem)
        {
            $data[] = [
                'id' => $mItem->getId(),
                'namespace' => $mItem->getNamespace(),
                'icon' => $mItem->getIcon(),
                'href' => method_exists($mItem, 'getHref') ? $mItem->getHref() : null,
                'htmlAtributes' => $mItem->getHtmlAttributes(),
                'class' => $mItem->getClasses(),
                'clickAction' => $this->parseOnClickAction($mItem->getHtmlAttributes()['@click']),
                'title' => $this->lang->tr($this->id, $mItem->getTitle())
            ];
        }

        return $data;
    }
    
    public function add($sidebar)
    {
        $this->ajaxMenuElements[$sidebar->getId()] = $sidebar;
        
        if (method_exists($sidebar, 'setParent'))
        {
            $sidebar->setParent($this);
        }
        
        return  $this;
    }
    
    public function parseOnClickAction($actionString)
    {
        if (stripos($actionString, '(') > 0)
        {
            $actions = explode('(', $actionString);
            $action = $actions[0];
            $paramsString = trim(trim(trim($actions[1], ';'), ')'), "'");
            $params = explode(',', $paramsString);
            foreach ($params as $key => $param)
            {
                $params[$key] = trim(trim(trim($param), "'"), '"');
            }
            
            return ['action' => $action, 'params' => $params];
        }
        
        return ['action' => $actionString, 'params' => []];
    }
}
