<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Autoresponders extends ProviderApi
{

    public function read()
    {
        parent::read();
    }

    public function create()
    {
        parent::create();

        $data = [
            'domain'      => $this->formData['domain'],
            'user'        => $this->formData['address'],
            'text'        => html_entity_decode($this->formData['message']),
        ];
        if($this->formData['cc'])
        {
            $data['cc']    = 'ON';
            $data['email'] = $this->formData['cc'];
        }
        $this->userApi->autoresponder->create(new Models\Command\Autoresponder($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('autoresponderHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'domain'  => $this->formData['domain'],
            'user'    => $this->formData['user']
        ];
        $this->userApi->autoresponder->delete(new Models\Command\Autoresponder($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('autoresponderHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $data = [
            'domain' => $this->formData['domain'],
            'user'   => $this->formData['address'],
            'text'   => html_entity_decode($this->formData['message']),
        ];
        if($this->formData['cc'])
        {
            $data['cc']    = 'ON';
            $data['email'] = $this->formData['cc'];
        }
        $this->userApi->autoresponder->modify(new Models\Command\Autoresponder($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('autoresponderHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);

        foreach($domainsName as $key => $value)
        {
            $data[] = explode('@', $value);
        }

        foreach($data as $elem => $each)
        {
            $result[$each[1]][] = $each[0];
        }

        $this->userApi->autoresponder->deleteMany($result);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('manyAutorespondersHasBeenDeleted');

    }
}
