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

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Sidebar;

/**
 * Description of SidebarTrait
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
trait SidebarTrait
{
    protected $order;
    protected $children = [];
    /**
     * @var Sidebar
     */
    protected $parent;
    protected $active=false;
    
    /**
     * Add Sidebar
     * @param \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Sidebar\Sidebar $sidebar
     * @return \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Sidebar\Sidebar
     */
    public function add( $sidebar)
    {
        $this->children[$sidebar->getId()] = $sidebar;
        $sidebar->setParent($this);
        return  $this;
    }
    
    public function getChild($id){
        if(!isset($this->children[$id])){
            throw new \Exception(sprintf("Sidebar %s does not exist", $id));
        }
        return $this->children[$id];
    }
    
    public function hasChildren(){
        return !empty($this->children);
    }
    
    public function childrenDelete($id){
        if(!isset($this->children[$id])){
            throw new \Exception(sprintf("Sidebar children %s does not exist", $id));
        }
        unset ($this->children[$id]);
    }
    
    /**
     * @return Sidebar[]
     */
    public function getChildren(){
        return $this->children;
    }
    
    public function destroy(){
        unset($this->children);
        return $this;
    }
    
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function delete()
    {
        if ($this->id)
        {
            $this->parent->childrenDelete($this->id);
        }
    }
    
    public function isActive()
    {
        return $this->active===true;
    }

    public function setActive($active)
    {
        $this->active = (boolean)$active;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
}
