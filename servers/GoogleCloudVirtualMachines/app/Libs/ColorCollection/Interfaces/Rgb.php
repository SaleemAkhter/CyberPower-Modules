<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces;

/**
 * Description of Rgb
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
interface Rgb
{
    /**
     * @return mixed
     */
    public function toRgb();

    /**
     * @return mixed
     */
    public function toRgba();
}