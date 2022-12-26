<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Prepare;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Unit;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Lang\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;


/**
 * Class Prepare
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Prepare
{

    static $currentConfig = [];

    /**
     **
     * @var null|\ModulesGarden\OvhVpsAndDedicated\Core\Lang\Lang
     */
    private static function getLang()
    {
        return ServiceLocator::call('lang');
    }

    public static function name($name)
    {
        $name = preg_replace('/[0-9]+/', '', $name);
        $name = preg_replace('/\s\s+/', ' ', rtrim($name));
        return $name;
    }

    public static function vpsCategory($response)
    {
        $out = [];
        foreach ($response as $category)
        {
            $out[$category['family']] = self::getLang()->absoluteTranslate($category['family']);
        }
        return $out;
    }

    public static function vpsDistributionVersion($response)
    {
        $out = [];
        foreach ($response as $version => $bits)
        {
            foreach (array_keys($bits) as $bit)
            {
                $versionToDisplay = ucfirst($version);
                if(!isset($out["$version:$bit"]))
                {
                    $out["$version:$bit"] = "$versionToDisplay $bit";

                    if(!empty($bits[$bit][0]['baseName']))
                    {
                        $out["$version:$bit"] .= ' - '.$bits[$bit][0]['baseName'];
                    }
                }
            }
        }
        asort($out);

        return $out;
    }

    public static function vpsProduct($response)
    {
        $out = [];
        foreach ($response as $category)
        {
            $out[$category['planCode']] = $category['details']['product']['description'];
        }
        return $out;
    }

    /**
     * @param $response
     * @return array
     */
    public static function vpsOS($response)
    {
        $out = [];
        foreach ($response as $os => $item)
        {
            $out[$item] = $item;
        }
        asort($out);

        return $out;
    }

    public static function vpsOptions($response)
    {
        $out = [];
        foreach ($response as $item)
        {
            $out[$item['family']][] = $item;
        }
        return $out;
    }

    public static function vpsDistribution($response)
    {
        $out = [];
        foreach ($response as $key => $value)
        {
            $out[$key] = ucfirst($key);
        }
        asort($out);

        return $out;
    }

    public static function vpsDistributionLanguages($response)
    {
        $out = [];
        foreach ($response as $language)
        {
            $lang                       = self::getLang()->absoluteTranslate($language['language']);
            $out[$language['language']] = isset($lang) ? $lang : $language['language'];
        }
        asort($out);

        return $out;
    }

    public static function vpsLocalization($response)
    {
        $out = [];
        foreach ($response as $item)
        {
            $location = strtolower( $item);
            $out[$item] = self::getLang()->absoluteTranslate('mainContainer', 'configurableOptions',$location);
        }
        asort($out);

        return $out;
    }

    public static function vpsOptionsValues($values)
    {
        $out = [];
        foreach ($values as $value)
        {
            $out[$value['invoiceName']] = $value['invoiceName'];
        }

        return $out;
    }

    public static function getProductTechnicalDetails($product)
    {
        $skipDetails      = ['core_frequency'];

        $technicalDetails = $product['details']['product']['technicalDetails'];
        $out              = '(';
        foreach ($technicalDetails as $value)
        {
            if (in_array($value['key'], $skipDetails))
            {
                continue;
            }
            $result = Unit::getValueWithUnit($value['value']);

            $lang   = self::getLang()->absoluteTranslate($value['key']);
            $out .= "{$result} {$lang}";

            if($value['key'] == end($technicalDetails)['key'])
            {
                continue;
            }
            $out .= '/';
        }
        $out .= ')';
        return $out;
    }
}