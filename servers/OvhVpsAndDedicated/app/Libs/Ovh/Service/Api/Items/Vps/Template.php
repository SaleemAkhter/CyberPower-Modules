<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Software;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Template\Template as TemplateModel;

/**
 * Class Template
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Template extends AbstractApiItem
{
    public function software()
    {
        return new Software($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function model()
    {
        return new TemplateModel($this->getInfo());
    }
}