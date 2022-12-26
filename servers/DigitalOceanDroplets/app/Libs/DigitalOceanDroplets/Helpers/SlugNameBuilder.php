<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Slug;
use DigitalOceanV2\Entity\Size;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

class SlugNameBuilder
{
    protected $predefinedNames;

    public function __construct()
    {
        $predefinedPrefixes = ['s', 'c', 'c2', 'g', 'gd', 'm', 'm3', 'm6', 'so', 'so1_5', 'empty'];

        foreach ($predefinedPrefixes as $prefix) {
            $this->predefinedNames[$prefix] = Lang::getInstance()->T('slug-' . $prefix);
        }
    }


    public function buildSlug(Size $slug): string
    {
        $format = Lang::getInstance()->T('slug-format');
        $explode = explode('-', $slug->slug);
        $prefix = array_shift($explode);

        $cpuType = '';
        if(strpos($slug->slug, 'intel') || strpos($slug->slug, 'amd'))
        {
            $cpuType = '-' . strtoupper(strrchr($slug->slug, '-'));
        }
        $name = $this->predefinedNames[$prefix] ?: $this->predefinedNames['empty'];
        $memory = $slug->memory/1024;
        $vcpus = $slug->vcpus;
        $disk = $slug->disk;
        $transfer = $slug->transfer;
        $price = $slug->priceMonthly;

        return sprintf($format, $name, $cpuType, $memory, $vcpus, $disk, $transfer, $price);
    }
}