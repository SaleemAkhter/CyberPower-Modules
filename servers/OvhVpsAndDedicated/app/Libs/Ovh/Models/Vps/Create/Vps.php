<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Create;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\ProductConfig;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends Serializer
{

    private $productConfig;

    public function __construct(ProductConfig $productConfig)
    {
        $this->productConfig = $productConfig;
    }


    public function getItemOrderParams()
    {
        return [
            'duration'    => $this->generateDuration($this->productConfig->getDuration()),
            'planCode'    => $this->productConfig->getProduct(),
            'pricingMode' => 'default',
            'quantity'    => 1,
        ];
    }

    public function generateDuration($duration)
    {
        $base = 'P%sM';
        return sprintf($base, $duration);
    }

    public function getProductPlanCodeParams()
    {
        return ['planCode' => $this->productConfig->getProduct()];
    }

    public function createRequiredConfiguration($requireds)
    {
        $out = [];
        foreach ($requireds as $required)
        {
            if ($required['required'] != 1)
            {
                continue;
            }
            $value = $this->getValue($required);
            if (!in_array($value, $required['allowedValues']))
            {
                continue;
            }
            $out [] = $this->createRequiredOption($required['label'], $value);
        }
        return $out;
    }

    public function createRequiredOption($label, $value)
    {
        return [
            'label' => $label,
            'value' => $value,
        ];
    }

    public function getValue($requiredOption)
    {
        $type = $this->getType($requiredOption['label']);
        switch ($type)
        {
            case 'datacenter':
                return strtoupper($this->productConfig->getLocalization());
            case 'os':
                return $this->productConfig->getSystemVersions();
        }
    }

    public function getType($label)
    {
        $exploded = explode('_', $label);
        return $exploded[count($exploded) - 1];
    }

    public function createOptions($possiblyOptions, $itemId)
    {
        $options = $this->productConfig->getOptions();
        $out = [];
        foreach ($possiblyOptions as $option)
        {
            if(in_array($option['planCode'], $options))
            {
                $out[] = [
                    'duration' => $this->generateDuration($this->productConfig->getDuration()),
                    'itemId' => $itemId,
                    'planCode' => $option['planCode'],
                    'pricingMode' => 'default',
                    'quantity' => 1
                ];
            }
        }
        return $out;
    }
}
