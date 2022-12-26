<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\Interfaces\Patterns;

/**
 * Description of CollectionInterface
 *
 * @author Tomasz Bielecki<tomasz.bi@modulesgarden.com>
 */
interface CollectionInterface
{
    /**
     * @param array $array
     * @return mixed
     */
    public function setCollection($array = []);
}