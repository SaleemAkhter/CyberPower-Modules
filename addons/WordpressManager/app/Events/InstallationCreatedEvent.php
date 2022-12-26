<?php

namespace ModulesGarden\WordpressManager\App\Events;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\Events\Event;

/**
 * Class MyTestEvent
 * @package ModulesGarden\WordpressManager\App\Events
 */
class InstallationCreatedEvent extends Event
{
    /**
     * @var Installation
     */
    protected $installation;

    /**
     * MyTestEvent constructor.
     * @param $data
     */
    public function __construct(Installation $installation)
    {
        $this->installation = $installation;
    }

    /**
     * @return Installation
     */
    public function getInstallation()
    {
        return $this->installation;
    }




}