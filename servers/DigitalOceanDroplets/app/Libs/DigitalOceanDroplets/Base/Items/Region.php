<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\AbstractItems;

/**
 * Description of Size
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 * 
 */
class Region extends AbstractItems
{

    protected $primaryKey = 'slug';
    protected $itemType   = 'region';

}
