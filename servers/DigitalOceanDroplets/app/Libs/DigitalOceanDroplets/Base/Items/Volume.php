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
class Volume extends AbstractItems
{

    protected $primaryKey = 'id';
    protected $itemType   = 'volume';

}
