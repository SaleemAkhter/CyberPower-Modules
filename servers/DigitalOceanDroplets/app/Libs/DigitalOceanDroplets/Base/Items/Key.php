<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\AbstractItems;

/**
 * Description of Image
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 * 
 * @method public update(string $name) 
 * @method public delete() 
 * @method public transfer(string $regionSlug) 
 * @method public convert(integer $actionID) 
 */
class Key extends AbstractItems
{

    protected $itemType = 'key';

}
