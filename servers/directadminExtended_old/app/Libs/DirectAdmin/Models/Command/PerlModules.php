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

class PerlModules extends AbstractModel implements ResponseLoad
{
    protected $list = [];

    public function loadResponse($response, $function = null)
    {
        $this->addResponseElement(new self(['list' => $response['list']]));

        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param array $list
     * @return PerlModules
     */
    public function setList(array $list)
    {
        $this->list = $list;
        return $this;
    }

}