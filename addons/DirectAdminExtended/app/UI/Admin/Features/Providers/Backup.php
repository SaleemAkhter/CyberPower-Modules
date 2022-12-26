<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

use ModulesGarden\DirectAdminExtended\App\Services\BackupPathService;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Server;
use ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;


class Backup extends BaseDataProvider
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;

        $serversSelect  = [];
        $servers        = Server::where('type', 'directadminExtended')->get();
        foreach ($servers as $server)
        {
            $serversSelect[$server->id] = $server->name;
        }
        $this->data['server_id']                  = [];
        $this->availableValues['server_id']       = $serversSelect;
    }

    public function create()
    {
        $ftpEndService = new BackupPathService();
        $ftpEndService->create(array_merge($this->formData, ['product_id' => $this->getRequestValue('pid')]));

        return (new RawDataJsonResponse())->setMessageAndTranslate('backupDirectoryPathCreated');
    }

    public function update()
    {
        $ftpEndService = new BackupPathService($this->formData['id']);
        $ftpEndService->update(array_merge($this->formData, ['product_id' => $this->getRequestValue('pid')]));

        return (new RawDataJsonResponse())->setMessageAndTranslate('backupDirectoryPathUpdated');
    }

    public function delete()
    {
        $ftpEndService = new BackupPathService($this->formData['id']);
        $ftpEndService->delete();

        return (new RawDataJsonResponse())->setMessageAndTranslate('backupDirectoryPathDeleted');
    }
}
