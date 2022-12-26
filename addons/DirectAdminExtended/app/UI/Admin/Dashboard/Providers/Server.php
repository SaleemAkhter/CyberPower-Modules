<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Providers;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\DirectAdminExtended\App\Models\ServerSettings;
use ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;

class Server extends BaseDataProvider
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
        if (ServerSettings::factory()->exists($this->actionElementId))
        {
            $server                     = ServerSettings::factory($this->actionElementId);
            $this->data['pleskPanel']   = $server->panel_login_hostname;
            $this->data['webmailPanel'] = $server->webmail_login_hostname;
        }
    }

    public function createOrUpdate()
    {
        ServerSettings::factory()->exists($this->formData['id']) ? $this->update() : $this->create();

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('serverSettingsUpdated');
    }

    public function create()
    {
        $data = [
            'server_id'              => $this->formData['id'],
            'panel_login_hostname'   => $this->formData['pleskPanel'],
            'webmail_login_hostname' => $this->formData['webmailPanel']
        ];
        ServerSettings::factory()->create($data);
    }

    public function update()
    {
        $data = [
            'panel_login_hostname'   => $this->formData['pleskPanel'],
            'webmail_login_hostname' => $this->formData['webmailPanel']
        ];
        ServerSettings::factory()->where('server_id', $this->formData['id'])->update($data);
    }

    public function delete()
    {
        
    }
}
