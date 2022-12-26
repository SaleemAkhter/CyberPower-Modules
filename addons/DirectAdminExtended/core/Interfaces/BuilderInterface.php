<?php

namespace ModulesGarden\DirectAdminExtended\Core\Interfaces;

/**
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
interface BuilderInterface
{
    /**
     * @return Bool
     */
    public function isCreate();
    
    /**
     * @return $this
     */
    public function enableCreate();
    
    /**
     * @return $this
     */
    public function disableCreate();
    
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return \ModulesGarden\DirectAdminExtended\Core\DI\Objects\DiConteiner|null
     */
    public function findConteiner($name);
    
    /**
     * 
     * @param object $object
     * @param string $method
     * @param string $name
     * 
     * @return mixed
     */
    public function call($object, $method, $name);
    
    /**
     * @param string $name
     */
    public function getContainer($name);
}
