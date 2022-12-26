<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Readers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\FileReader\Reader;

/**
 * Description of YmlColorReader
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
class ColorReader
{
    /**
     *
     * @var string 
     */
    protected $fileName = null;

    /**
     *
     * @var array 
     */
    protected $data = [];

    public function readConfigData()
    {
        if (!$this->fileName)
        {
            //todo throw new exception
        }

        /* prepare filename */
        $dir      = dirname(__DIR__) . DIRECTORY_SEPARATOR . "Configuration";
        $name     = $this->fileName;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $name;

        /* read data from file */
        $this->data = Reader::read($fullPath)->get();

        return $this;
    }

    public function setFileName($name)
    {
        $this->fileName = $name;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}