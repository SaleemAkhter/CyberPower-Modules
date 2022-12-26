<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\BaseConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Country;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Path;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Vps as VpsApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\InstallationTemplate;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @property $disks
 * @property $option
 */
class Dedicated extends AbstractApi
{

    public function server()
    {
        return new Server($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function installationTemplate()
    {
        return new InstallationTemplate($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }


}