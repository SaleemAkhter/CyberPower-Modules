<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\CollorCollection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Readers\ColorReader;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\ColorParser;

/**
 * Description of ColorFactory
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
class ColorFactory
{
    /**
     *
     * @var CollorCollection
     */
    protected $colleciton = null;

    /**
     *
     * @var ColorParser
     */
    protected $colorParser = null;
    
    /**
     *
     * @var ColorReader
     */
    protected $colorReader = null;
    
    /**
     * 
     * @param type $colorParser
     */
    public function __construct(ColorParser $colorParser)
    {
        $this->colleciton  = new CollorCollection();
        $this->colorReader = new ColorReader();
        /* set color parser*/
        $this->setColorParser($colorParser);
    }

    /**
     * 
     * @param type $type
     * @param type $fileName
     * @return CollorCollection
     */
    public function getColors($fileName)
    {
        /* read collors from config */
        $this->colorReader->setFileName($fileName);
        $this->colorReader->readConfigData();

        /* parse readedData */
        $parser = $this->colorParser;

        /* color array list */
        $arrayData = $parser::parseColors($this->colorReader->getData());
        
        /* set color to collection */
        $this->colleciton->setCollection($arrayData);

        return $this->colleciton;
    }

    /**
     * 
     * @param ColorParser $parser
     * @return $this
     */
    public function setColorParser(ColorParser $parser)
    {
        $this->colorParser = $parser;
        return $this;
    }
}