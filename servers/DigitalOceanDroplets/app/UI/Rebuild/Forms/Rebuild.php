<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\RebuildDropletFieldsHelper;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Helpers\ImageManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

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
        $this->setProvider(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Providers\Rebuild());
        $this->addField($this->getOsTemplateField());
        $this->setConfirmMessage('confirmRebuild');
        $this->addButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Buttons\RebuildConfirm());
        $this->loadDataToForm();
    }

    private function getOsTemplateField()
    {

        $osTemplate = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select2vue('osTemplate');

        $data = RebuildDropletFieldsHelper::getAvailableImages($this->whmcsParams);

        $osTemplate->setAvalibleValues($data);
        return $osTemplate;
    }

    private function getAllTemplates()
    {

        $api    = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api($this->whmcsParams);
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
