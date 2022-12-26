<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\InstallationTemplate as InstallationTemplateItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Blocker;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class InstallationTemplate extends AbstractApi
{
    public function all()
    {
        $templates = $this->get();
        asort($templates);
        return $templates;
    }

    public function allWithKey()
    {
        $templates = $this->get();
        asort($templates);
        $templates = array_combine($templates, $templates);
        return $templates;
    }

    public function allToModel()
    {
        $items = $this->all();
        $collection = new Collection();
        foreach ($items as $item)
        {
            $collection->add($this->one($item)->model());
        }
        return $collection->all();
    }

    public function allToArray()
    {
        $items = $this->all();
        $collection = new Collection();
        foreach ($items as $item)
        {
            $collection->add($this->one($item)->getInfo());
        }
        return $collection->all();
    }

    public function one($templateName)
    {
        return new InstallationTemplateItem($this->api, $this->client, $this->getPathExpanded($templateName));
    }
    
    public function getCompatibleTemplates(Server $server, $withKeys = false)
    {
        $templatesResult = $server->install()->compatibleTemplates();
        $templates = $templatesResult['ovh'];
   
        $blockedImages = Blocker::getBlockedImages();
        if(is_array($blockedImages) && count($blockedImages) > 0)
        {
            $templates = array_diff($templates, $blockedImages);
        }
        
        asort($templates);
        if($withKeys)
        {
            $templates = array_combine($templates, $templates);
        }     
        return $templates;
    }

}