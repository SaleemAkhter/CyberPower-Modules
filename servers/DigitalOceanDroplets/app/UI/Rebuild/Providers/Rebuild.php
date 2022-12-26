<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Helpers\ImageManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Rebuild extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
    }

    public function create()
    {
        
    }

    public function delete()
    {
        
    }

    public function update()
    {
        try
        {
            $imageManager = new ImageManager($this->whmcsParams);
            $imageManager->rebuildMachine($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('rebuildFromImageStart');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
