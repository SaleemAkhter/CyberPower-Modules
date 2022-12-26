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

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Sidebar;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;
/**
 * Description of Sidebar
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Sidebar extends BaseContainer
{
    use SidebarTrait;
    
    protected $href;
    
    public function __construct($id, $href = null, $order = null)
    {
        $this->href  = $href;
        $this->order = $order;

        parent::__construct($id);
    }
    
    public function getHref()
    {
        return $this->href;
    }

    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    public function get(){
        $children =[];
        foreach($this->children as $child){
            if(!$child->getOrder()){
                $children[]= $child;
                continue;
            }
            $children[$child->getOrder()]= $child;
        }
        ksort($children);
        return $children;
    }

}
