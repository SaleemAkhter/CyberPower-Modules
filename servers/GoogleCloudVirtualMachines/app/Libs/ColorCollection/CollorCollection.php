<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\Randomize;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\Interfaces\Patterns\CollectionInterface;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\ColorFactory;

/**
 * Description of CollocCollection
 *
 * @author inbs
 */
class CollorCollection implements CollectionInterface, \Iterator
{
    /**
     *
     * @var array 
     */
    protected $collorColleciton = [];
    
    /**
     *
     * @var int 
     */
    protected $position         = 0;
    /**
     *
     * @var int 
     */
    protected $lastPosition     = null;

    /**
     * 
     */
    public function __construct()
    {

    }

    /**
     * 
     * @return type
     */
    public function getNextColor()
    {
        $position = $this->getNextPosition();

        if ($this->collorColleciton[$this->position] === null)
        {
            $this->rewindPreviousPosition();

            if($this->collorColleciton[$this->position] instanceof Randomize)
            {
                $this->collorColleciton[$this->position]->random();
            }
        }

        return $this->collorColleciton[$this->position];
    }

    /**
     * 
     * @description
     * @param type $position
     * @param type $offset
     * @return type
     */
    public function getRange($position = 0, $offset = 1)
    {
        $tempData = [];

        for ($position; $position <= ($position + $offset); $position++)
        {
            $tempData[$position] = $this->collorColleciton[$position];
        }

        return $tempData;
    }

    /**
     * 
     * @description rewind to first position
     * @return $this
     */
    public function rewind()
    {
        $this->lastPosition = $this->position;
        $this->position     = 0;
        return $this;
    }

    /**
     * @return int
     */
    protected function getNextPosition()
    {
        $this->lastPosition = $this->position;
        return $this->position++;
    }

    /**
     * @return int
     */
    protected function getLastPosition()
    {
        return $this->lastPosition;
    }

    /**
     * @return $this
     */
    protected function rewindPreviousPosition()
    {
        $this->position = $this->lastPosition;

        return $this;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->collorColleciton[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return type|void
     */
    public function next()
    {
        return $this->getNextColor();
    }

    /**
     * @return bool|void
     */
    public function valid()
    {
        
    }

    /**
     * @param array $array
     * @return $this
     */
    public function setCollection($array = [])
    {
        $this->collorColleciton = $array;
        return $this;
    }
}