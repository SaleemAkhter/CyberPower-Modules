<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;


class RedirectButton extends BaseContainer
{
    protected $id             = 'redirectButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip '];
    protected $icon           = 'lu-zmdi lu-zmdi-rotate-right';
    protected $title          = 'redirectButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];    
    
    protected $rawUrl = null;
    protected $redirectParams = [];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'redirect($event, ' . $this->parseCustomParams() . ')';        
    }

    protected function parseCustomParams()
    {
        if (count($this->redirectParams) === 0 && $this->rawUrl === null)
        {
            return '{}';
        }

        return $this->parseListTOJsString($this->redirectParams);
    }
    
    protected function parseListTOJsString($params)
    {
        $jsString = '{';
        
        if ($this->rawUrl !== null)
        {
            $params['rawUrl'] = $this->rawUrl;
        }        
        
        foreach ($params as $key => $value)
        {
            $jsString .= ' ' . str_replace('-', '__', $key) . ': ' . (is_array($value) ? ($this->parseListTOJsString($value) . ',') : ("'" . (string) $value) . "',");
        }
            
        $jsString = trim($jsString, ',') . ' } ';
        
        return $jsString;
    }
    
    public function setRawUrl($url)
    {
        $this->rawUrl = $url;
        
        return $this;
    }
    
    public function addRedirectParam($key, $value)
    {
        $this->redirectParams[$key] = $value;
        
        $this->updateHtmlAttributesByRedirectParams();
        
        return $this;
    }
    
    public function setRedirectParams($paramsList)
    {
        $this->redirectParams = $paramsList;
        
        $this->updateHtmlAttributesByRedirectParams();
        
        return $this;
    }
    
    protected function updateHtmlAttributesByRedirectParams()
    {
        foreach ($this->redirectParams as $key => $value)
        {
            $this->updateHtmlAttribute($key, $value);
        }
    }
    
    protected function updateHtmlAttribute($key, $value)
    {
        if (strpos($value, ':') === 0)
        {
            $this->addHtmlAttribute(':data-' . $key , 'dataRow.' . trim($value, ':'));
        }
    }
}