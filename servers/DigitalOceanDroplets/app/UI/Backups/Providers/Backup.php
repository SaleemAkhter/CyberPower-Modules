<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers\BackupsManager;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers\BackupManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Backup extends BaseDataProvider implements ClientArea, AdminArea
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
            $snapshotManager = new BackupsManager($this->whmcsParams);
            $snapshotManager->restoreFromBackup($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('restoreFromBackupStart');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }
    
}
