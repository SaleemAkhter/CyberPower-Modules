<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Events;

use ModulesGarden\Servers\DirectAdminExtended\Core\Events\Event;

/**
 * Class MyTestEvent
 * @package ModulesGarden\Servers\DirectAdminExtended\App\Events
 */
class MyTestEvent extends Event
{
    protected $data = null;

    /**
     * MyTestEvent constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }
}