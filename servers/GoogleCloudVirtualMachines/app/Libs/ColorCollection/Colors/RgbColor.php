<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Colors;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\Randomize;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\Rgb;

/**
 * Description of RgbColor
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
class RgbColor implements Rgb, Randomize
{
    const RED_IN_ARRAY   = 'r';
    const GREEN_IN_ARRAY = 'g';
    const BLUE_IN_ARRAY  = 'b';
    const ALFA_IN_ARRAY  = 'a';
    /**
     *
     * @var int 
     */
    protected $red = null;

    /**
     *
     * @var int 
     */
    protected $green = null;

    /**
     *
     * @var int 
     */
    protected $blue = null;

    /**
     *
     * @var int 
     */
    protected $alfa = null;

    /**
     * 
     * @param type $r
     * @param type $g
     * @param type $b
     * @param type $a
     */
    public function __construct($r = null, $g = null, $b = null, $a = null)
    {
        $this->red   = $r;
        $this->green = $g;
        $this->blue  = $b;
        $this->alfa  = $a;
        ;
    }

    public function getRed()
    {
        return $this->red;
    }

    public function getGreen()
    {
        return $this->green;
    }

    public function getBlue()
    {
        return $this->blue;
    }

    public function getAlfa()
    {
        return $this->alfa;
    }

    public function setRed($red = 0)
    {
        $this->red = $this->getCorrectValueColor($red);
        return $this;
    }

    public function setGreen($green = 0)
    {
        $this->green = $this->getCorrectValueColor($green);
        return $this;
    }

    public function setBlue($blue = 0)
    {
        $this->blue = $this->getCorrectValueColor($blue);
        return $this;
    }

    public function setAlfa($alfa = 0)
    {
        $this->alfa = $alfa;
        return $this;
    }

    /**
     * 
     * @description check if value is betwen 0 & 255
     * @param type $value
     * @return type
     */
    protected function getCorrectValueColor($value)
    {
        if ($value < 0)
        {
            $value = 0;
        }
        elseif ($value > 255)
        {
            $value = 255;
        }

        return (int) $value;
    }

    /**
     * 
     * @description return object params in array
     * @return array
     */
    public function toArray()
    {
        return [
            RgbColor::RED_IN_ARRAY   => $this->getRed(),
            RgbColor::GREEN_IN_ARRAY => $this->getGreen(),
            RgbColor::BLUE_IN_ARRAY  => $this->getBlue(),
            RgbColor::ALFA_IN_ARRAY  => $this->getAlfa(),
        ];
    }
    
    /**
     * 
     * @description return as RGB string
     * @return string
     */
    public function toRgb()
    {
        return "rgba({$this->red}, {$this->green}, {$this->blue})";
    }
    
    /**
     * 
     * @description return as RGBA string
     * @return string
     */
    public function toRgba()
    {
        return "rgba({$this->red}, {$this->green}, {$this->blue}, {$this->alfa})";
    }

    /**
     * @description return object with random parameters
     * @return mixed
     */
    public function random()
    {
        $this->setRed(random_int(0,255));
        $this->setGreen(random_int(0,255));
        $this->setBlue(random_int(0,255));

        return $this;
    }
}