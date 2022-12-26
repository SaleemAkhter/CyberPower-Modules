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

class SystemInfo extends AbstractModel implements ResponseLoad
{
    protected $php;
    protected $php2;


    public function loadResponse($response, $function = null)
    {
        return new self($response);

        $this->addResponseElement($self);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * @return mixed
     */
    public function getPhp2()
    {
        return $this->php2;
    }
}