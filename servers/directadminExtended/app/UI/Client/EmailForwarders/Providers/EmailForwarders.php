<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class EmailForwarders extends ProviderApi
{

    public function read()
    {
        $this->data['email'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'user'      => $this->formData['name'],
            'domain'    => $this->formData['domains'],
            'email'     => $this->formData['destination']
        ];

        $this->userApi->emailForwarder->create(new Models\Command\EmailForwarder($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('emailForwarderHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $explodeEmail   = explode('@', $this->formData['email']);
        $data           = [
            'user'   => $explodeEmail[0],
            'domain' => $explodeEmail[1]
        ];
        $this->userApi->emailForwarder->delete(new Models\Command\EmailForwarder($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('emailForwarderHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $data = [
            'user'      => $this->formData['name'],
            'domain'    => $this->formData['domains'],
            'email'     => $this->formData['destination']
        ];
        $this->userApi->emailForwarder->modify(new Models\Command\EmailForwarder($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('emailForwarderHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);

        foreach ($domainsName as $name) {

            $account = explode('@', $name);

            $data[$account[1]][] = $account[0];
        }
        $this->userApi->emailForwarder->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('forwarderDeleted');
    }

}
