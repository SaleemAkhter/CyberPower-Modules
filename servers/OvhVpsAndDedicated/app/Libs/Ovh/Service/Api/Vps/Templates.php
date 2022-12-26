<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Template as TemplateApiItem;

/**
 * Class Templates
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Templates extends AbstractApi
{
    public function all()
    {
        $reponse = $this->get();
        $collection = new Collection();
        foreach ($reponse as $item)
        {
            $collection->add($this->one($item));
        }
        return $collection->all();
    }

    public function one($id)
    {
        $response = $this->get($id);
        return new TemplateApiItem($this->api, $this->client, $this->getPathExpanded($id), $response);
    }

    public function allToModel()
    {
        $all = $this->all();
        foreach ($all as &$item)
        {
            $item = $item->model();
        }
        return $all;
    }


}