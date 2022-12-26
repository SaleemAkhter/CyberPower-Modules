<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ApplicationsBackup extends Applications
{

    public function create()
    {
        $data = [
            'backupins' => 1
        ];
        $this->loadApplicationAPI();
        $this->api->backupCreate($this->formData['id'], new Backup($data));
        return (new ResponseTemplates\HtmlDataJsonResponse())
                        ->setMessageAndTranslate('backupHasBeenCreated')
                        ->addRefreshTargetId('BackupsTable');
    }

    public function update()
    {
        $this->loadApplicationAPI();
        $this->api->backupRestore($this->formData['id']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('backupHasBeenRestored');
    }

    public function delete()
    {
        $this->loadApplicationAPI();
        $this->api->backupDelete($this->formData['id']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('backupHasBeenDeleted');
    }
}
