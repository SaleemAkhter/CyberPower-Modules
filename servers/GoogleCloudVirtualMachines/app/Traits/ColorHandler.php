<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\ColorFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Colors\RgbColor;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\ColorParser;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Parsers\RgbYmlParser;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\CollorCollection;

/**
 * Description of ColorFactoryComponent
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
trait ColorHandler
{    
    /**
     *
     * @var ColorParser
     */
    protected $colorParser  = null;
    
    /**
     *
     * @var string 
     */
    protected $configName = 'defaultRgbColors.yml';
    
    /**
     *
     * @var CollorCollection
     */
    protected $collorCollection;
    
    /**
     * 
     * @param ColorParser $parser
     */
    public function setColorParser(ColorParser $parser)
    {
        $this->colorParser = $parser;
    }
    
    /**
     * 
     * @return ColorFactory
     */
    public function getColorFactory()
    {
        $parser = $this->getColorParser();
        
        return new ColorFactory($parser);
        
    }
    
    /**
     * 
     * @return ColorParser
     */
    protected function getColorParser()
    {
        if(!$this->colorParser)
        {
            $this->colorParser = new RgbYmlParser();
        }
        
        return $this->colorParser;
    }
    
    /**
     * 
     * @return CollorCollection
     */
    public function getColorCollection()
    {
        if(!$this->collorCollection)
        {
            $factory           = $this->getColorFactory();
            $this->collorCollection = $factory->getColors($this->configName);
        }
        
        return $this->collorCollection;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getNextColor(): RgbColor
    {
        return $this->getColorCollection()->getNextColor();
    }
    
}