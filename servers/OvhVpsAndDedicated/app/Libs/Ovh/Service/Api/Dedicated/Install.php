<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;

/**
 * Class Install
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Install extends AbstractApi
{
    public function compatibleTemplates()
    {
        return $this->get(__FUNCTION__);
    }

    public function start($params)
    {
        return $this->post(__FUNCTION__, $params);
    }
}