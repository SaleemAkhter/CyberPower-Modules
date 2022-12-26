<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Ip;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;

/**
 * Class Reverse
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reverse extends AbstractApiItem
{
    /**
     * @param $params array(ipReverse => required , reverse => required)
     * @return mixed
     */
    public function add($params)
    {
        return $this->post(false, $params);
    }

    public function one($ipReverse)
    {
        return $this->get($ipReverse);
    }
}