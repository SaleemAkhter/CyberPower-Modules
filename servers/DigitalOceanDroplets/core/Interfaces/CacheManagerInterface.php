<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Interfaces;

/**
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
interface CacheManagerInterface
{
    /**
     * @param string $key
     * @return $this
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function setKey($key = 'default');
    
    /**
     * @return string
     */
    public function getKey();
    
    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data = null);
    
    /**
     * @return mixed
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function getData();
    
    /**
     * @return $this
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function seve();
    
    /**
     * @return $this
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function remove();
    
     /**
     * @return $this
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function clearAll();
    
    /**
     * @return type
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException
     */
    public function exist();
}
