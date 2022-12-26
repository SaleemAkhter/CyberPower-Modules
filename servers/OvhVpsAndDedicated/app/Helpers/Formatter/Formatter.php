<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Class Formatter
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Formatter
{
    const UCFIRST = 'ucfirst';
    const STRTOUPPER = 'strtoupper';


    public static function formatForDisplay(array $collection, array $toFormat)
    {
        foreach ($collection as &$item)
        {
            $item = self::format($item, $toFormat);
        }
        return $collection;
    }

    /**
     * @param array $collection
     * @param array $toFormat $field => $method
     * @return mixed
     */
    public static function format($collection, $toFormat)
    {
        foreach ($collection as $key => &$value)
        {
            $method = $toFormat[$key];

            if(!function_exists($method))
            {
                continue;
            }

            $value  = $method($value);
        }
        return $collection;
    }

    public static function formatToNameAndValueWithLangedName($table = [], $langedName = true)
    {
        $lang = ServiceLocator::call('lang');
        $out = [];
        foreach ($table as $key => $value)
        {
            $out[] = [
                'name'  => $lang->absoluteTranslate($key),
                'value' => $value,
            ];
        }

        return $out;
    }
}