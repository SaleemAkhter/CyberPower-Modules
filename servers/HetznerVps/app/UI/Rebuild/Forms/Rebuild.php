<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Forms;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Rebuild extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'rebuildForm';
    protected $name  = 'rebuildForm';
    protected $title = 'rebuildManage';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Providers\Rebuild());
        $this->addField($this->getOsTemplateField());
        $this->setConfirmMessage('confirmRebuild');
        $this->addButton(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Buttons\RebuildConfirm());
        $this->loadDataToForm();
    }

    private function getOsTemplateField()
    {

        $osTemplate = new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select2vue('osTemplate');
        $osTemplate->setAvailableValues($this->getAllTemplates());
        return $osTemplate;
    }

    private function getAllTemplates()
    {

        $api    = new \ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api($this->getwhmcsParams());
        $images = $api->image->all();

        return $this->preparePrettyTable($images);
    }

    private function preparePrettyTable($data)
    {
        $allImages = [];
        foreach ($data as $item)
        {
            $allImages[$item->id] = $item->distribution . ' ' . $item->name;
        }
        return $allImages;
    }

}
