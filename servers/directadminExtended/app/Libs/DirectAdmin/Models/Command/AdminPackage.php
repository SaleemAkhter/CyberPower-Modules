<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class AdminPackage extends AbstractModel implements ResponseLoad
{
    protected $name;

    public function loadResponse($response, $function = null)
    {
        foreach($response['list'] as $package)
        {
            $self = new self(['name' => $package]);
            $this->addResponseElement($self);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Package
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}
