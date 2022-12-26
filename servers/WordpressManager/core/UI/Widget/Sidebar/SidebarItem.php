<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Nov 19, 2018)
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

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar;

use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
/**
 * Description of Sidebar
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class SidebarItem extends BaseContainer
{
    
    use SidebarTrait;
    
    public function __construct($id, $href = null, $order = null, $icon = null)
    {
        if ($href)
        {
            $this->setHref($href);
        }
        
        $this->order = $order;
        $this->icon = $icon;
        parent::__construct($id);
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getHref()
    {
        return $this->href;
    } 

    public function setHref($href)
    {
        $this->htmlAttributes['href']= $href;
        return $this;
    }
    
    public function setTarget($target)
    {
        $this->htmlAttributes['target'] = $target;
        
        return $this;
    }

    public function setHtml($html)
    {
        $this->html = $html;
        
        return $this;
    }



}
