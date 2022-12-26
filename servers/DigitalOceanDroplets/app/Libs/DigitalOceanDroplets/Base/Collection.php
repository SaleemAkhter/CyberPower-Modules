<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base;

/**
 * Description of Collection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Collection
{

    private $items = [];

    public function add($item)
    {
        array_push($this->items, $item);
    }

    public function all()
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items[0];
    }

}
