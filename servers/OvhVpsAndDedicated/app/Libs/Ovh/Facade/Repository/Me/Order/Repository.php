<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Me\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Me\Order;

/**
 * Class Repository
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Repository extends BaseRepository
{
    public function get($id)
    {

        return new Order($id);
    }
}