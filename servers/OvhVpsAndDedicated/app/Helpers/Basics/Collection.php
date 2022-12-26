<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics;

/**
 * Description of Collection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Collection
{
    private $items = [];

    public function add($item = null)
    {
        if(is_null($item))
        {
            return;
        }
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

    public function create($collection = [])
    {
        if(empty($collection))
        {
            return;
        }
        foreach ($collection as $item)
        {
            $this->add($item);
        }
    }
}
