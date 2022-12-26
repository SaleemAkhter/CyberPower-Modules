<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;


class CustomActionButton extends BaseContainer
{
    protected $id             = 'customActionButton';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'customActionButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];    
        
    protected $customActionName = null;
    protected $customActionParams = [];
    
    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'makeCustomActiom(' . $this->customActionName . ', ' . $this->parseCustomParams() . ', $event. ' . $this->getNamespace() . ', ' . $this->getIndex() . ')';        
    }
    
    public function setCustomActionName($name)
    {
        $this->customActionName = $name;
        
        return $this;
    }
    
    public function setCustomActionParams(array $params)
    {
        $this->customActionParams = $params;
        
        return $this;
    }
    
    public function addCustomActionParam($key, $value)
    {
        $this->customActionParams[$key] = $value;
        
        return $this;
    }
    
    protected function parseCustomParams()
    {
        if (count($this->customActionParams) === 0)
        {
            return '{}';
        }
        
        return $this->parseListTOJsString($this->customActionParams);
    }
    
    protected function parseListTOJsString($params)
    {
        $jsString = '{';
        foreach ($params as $key => $value)
        {
            $jsString .= ' ' . $key . ': ' . (is_array($value) ? ($this->parseListTOJsString($value) . ',') : ("'" . (string) $value) . "',");
        }
            
        $jsString = trim($jsString, ',') . ' } ';
        
        return $jsString;
    }
            
}