<?php

namespace ModulesGarden\DirectAdminExtended\App\Events;

use ModulesGarden\DirectAdminExtended\Core\Events\Event;

/**
 * Class MyTestEvent
 * @package ModulesGarden\DirectAdminExtended\App\Events
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