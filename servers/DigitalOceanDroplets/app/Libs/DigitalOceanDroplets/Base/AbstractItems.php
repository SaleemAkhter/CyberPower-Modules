<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base;

/**
 * Description of Items
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
abstract class AbstractItems
{

    private $itemObject;
    protected $itemType;
    protected $primaryKey = 'id';
    protected $api;

    public function __construct($item, $api)
    {
        $this->itemObject = $item;
        $this->api        = $api;
    }

    /*
     * Get property from item object
     * 
     * @param string $name
     * 
     * @return mixed object/string
     */

    public function __get($name)
    {
        $response = $this->itemObject->{$name};
        if( is_object($response) )
        {
            $itemClass = $this->getItemClass($name);
            if( class_exists($itemClass) )
            {
                return new $itemClass($response, $this->api);
            }
        }
        return $response;
    }

    /*
     * Call to item parent container
     * 
     * \DigitalOceanDropletsV2\Api
     * 
     */

    public function __call($name, $arguments = null)
    {
        try
        {
            if (method_exists($this->api->{$this->itemType}(), $name))
            {
                $response = $this->api->{$this->itemType}()->{$name}($this->itemObject->{$this->primaryKey}, ...$arguments);
                \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers\Logger::addLogs('1', $name, $arguments, $response);
                return $response;
            }
            throw new \Exception('Method does not exist on API.');
        }
        catch (\Exception $ex)
        {
            \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers\Logger::addLogs('1', $name, $arguments, $ex->getMessage());
            throw $ex;
        }
    }

    /*
     * Get Item class container
     * 
     * \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items;
     * 
     */

    private function getItemClass($itemName)
    {
        return __NAMESPACE__ . '\Items\\' . ucfirst($itemName);
    }

    public function getItemObject(){
        return $this->itemObject;
    }
}
