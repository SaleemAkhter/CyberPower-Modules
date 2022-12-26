<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;

/**
 * Class Collections
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Collections
{
    const TOARRAY = 'toArray';
    const JSON = 'json';

    public static function getInfoFromItem(AbstractApiItem $item)
    {
        return $item->getInfo();
    }

    public static function getInfoFromItemCollections(array $items)
    {
        $collection = new Collection();
        foreach ($items as $item)
        {
            $collection->add(self::getInfoFromItem($item));
        }
        return $collection->all();
    }

    public static function setIdFromAnotherFieldInArray($field, $items, $extraFields = [])
    {
        foreach ($items as $key => &$item)
        {
            foreach ($extraFields as $extraKey => $extraValue)
            {
                if(isset($item[$extraKey]))
                {
                    continue;
                }
                $item[$extraKey] = $extraValue;
            }
            $item['id'] = $field == self::JSON ? json_encode($items[$key]) : $item[$field];
        }
        return $items;
    }

    public static function getModelFromItem($collections)
    {
        foreach ($collections as &$item)
        {
            if(!$item instanceof AbstractApiItem)
            {
                continue;
            }
            $item  = $item->model();
        }
        return $collections;
    }

    public static function toArray($collections)
    {
        $collections = self::getModelFromItem($collections);
        foreach ($collections as &$item)
        {
            if(!method_exists ($item , SELF::TOARRAY))
            {
                continue;
            }
            $item  = $item->toArray();
        }
        return $collections;
    }
}