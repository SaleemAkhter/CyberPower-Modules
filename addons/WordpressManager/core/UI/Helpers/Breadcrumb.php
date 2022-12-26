<?php

namespace ModulesGarden\WordpressManager\Core\UI\Helpers;

/**
 * Breadcrumb
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Breadcrumb
{
    use \ModulesGarden\WordpressManager\Core\UI\Traits\Title;
    use \ModulesGarden\WordpressManager\Core\Traits\Lang;
    use \ModulesGarden\WordpressManager\Core\Traits\IsAdmin;
    
    protected $url = null;
    protected $order = 100;
        
    public function __construct($url = null, $title = null, $order = null, $rawTitle = null)
    {
        $this->setUrl($url);
        $this->setTitle($title);
        $this->setOrder($order);
        $this->setRawTitle($rawTitle);
    }
    
    public function setOrder($order = null)
    {
        if (is_int($order) && $order >= 0)
        {
            $this->order = $order;
        }
        
        return $this;
    }
    
    public function getOrder()
    {
        return $this->order;
    }    
    
    public function setUrl($url = null)
    {
        if (is_string($url) && $url !== '')
        {
            $this->url = $url;
        }
        
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function getBreadcrumb()
    {
        return ['url' => $this->url, 'title' => $this->buildTitle()];
    }
    
    public function buildTitle()
    {
        if ($this->getRawTitle())
        {
            return $this->getRawTitle();
        }
        
        $this->loadLang();
        
        return $this->lang->absoluteTranslate('addon' . ($this->isAdmin() ? 'AA' : 'CA'), 
                'breadcrumbs', $this->title);
    }
}
