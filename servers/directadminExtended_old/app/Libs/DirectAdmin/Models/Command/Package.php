<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Package extends AbstractModel implements ResponseLoad
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