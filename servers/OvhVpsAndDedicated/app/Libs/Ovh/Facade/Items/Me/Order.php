<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Me;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;


/**
 * Class Order
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method details()
 */
class Order extends BaseRepository
{
    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Me\Order
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($id, $params = [])
    {

        parent::__construct($params);

        $this->item = $this->api->me->order()->one($id);
        $this->methods = get_class_methods($this->item);
    }
}