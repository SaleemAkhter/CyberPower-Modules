<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ChangePassword;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Emails extends ProviderApi
{

    public function read()
    {
        $data = json_decode(base64_decode($this->actionElementId));
        $this->data['email'] = $data->email;
    }

    public function create()
    {
        parent::create();

        if ($this->formData['quota'] === 'on') {
            $quota = 0;
        }
        if ($this->formData['quota'] === 'off' && $this->formData['customQuota']) {
            $quota = $this->formData['customQuota'];
        }

        if ($this->formData['limit'] === 'on') {
            $limit = 0;
        }
        if ($this->formData['limit'] === 'off' && $this->formData['customLimit']) {
            $limit = $this->formData['customLimit'];
        }
        $data = [
            'user' => $this->formData['account'],
            'domain' => $this->formData['domains'],
            'password' => $this->formData['password'],
            'quota' => $quota,
            'limit' => $limit
        ];
        $this->userApi->email->create(new Models\Command\Email($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('emailHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $emailExplode = explode('@', $this->formData['email']);
        $data = [
            'user' => $emailExplode[0],
            'domain' => $emailExplode[1]
        ];
        $this->userApi->email->delete(new Models\Command\Email($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('emailHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        if (!empty($this->formData['oldPassword'])) {
            $data = [
                'oldPassword' => $this->formData['oldPassword'],
                'password' => $this->formData['password'],
                'ftpSwitcher' => ($this->formData['ftpAccount'] === 'off') ? 'no' : 'yes',
                'dbSwitcher' => ($this->formData['dbAccount'] === 'off') ? 'no' : 'yes',
                'daSwitcher' => ($this->formData['daAccount'] === 'off') ? 'no' : 'yes',
            ];

            $this->userApi->email->changePassword(new Models\Command\Email($data));

            if ($this->formData['daAccount'] == 'on') {
                $changePassword = new ChangePassword();
                $changePassword->changeWhmcsPassword($this->formData['password']);
            }

            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('emailAccountPasswordChanged');
        }

        if ($this->formData['quota'] === 'on') {
            $quota = 0;
        }
        if ($this->formData['quota'] === 'off' && $this->formData['customQuota']) {
            $quota = $this->formData['customQuota'];
        }

        if ($this->formData['limit'] === 'on') {
            $limit = 0;
        }
        if ($this->formData['limit'] === 'off' && $this->formData['customLimit']) {
            $limit = $this->formData['customLimit'];
        }
        if ($this->formData['limit'] === 'off' && !is_numeric($this->formData['customLimit'])) {
            $limit = '0';
        }

        $data = [
            'user' => $this->formData['account'],
            'newUser' => $this->formData['accountCopy'] ? $this->formData['accountCopy'] : $this->formData['account'],
            'domain' => $this->formData['domains'],
            'password' => $this->formData['password'],
            'quota' => $quota,
            'limit' => $limit
        ];

        $this->userApi->email->modify(new Models\Command\Email($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('emailHasBeenUpdated');
    }

    public function reload()
    {
        $unlimitedQuote = (is_null($this->formData['quota'])) ? $this->data['quota'] : $this->formData['quota'];
        $unlimitedSendLimit = (is_null($this->formData['limit'])) ? $this->data['limit'] : $this->formData['limit'];
        $this->data['account'] = (is_null($this->formData['account'])) ? $this->data['account'] : $this->formData['account'];
        $this->data['domains'] = (is_null($this->formData['domains'])) ? $this->data['domains'] : $this->formData['domains'];
        $this->data['password'] = (is_null($this->formData['password'])) ? $this->data['password'] : $this->formData['password'];

        if ($unlimitedQuote == 'off') {
            $this->data['quota'] = 'off';
            $this->data['customQuota'] = (is_null($this->formData['customQuota'])) ? $this->data['customQuota'] : $this->formData['customQuota'];
        } else {
            $this->data['quota'] = 'on';
        }

        if ($unlimitedSendLimit == 'off') {
            $this->data['limit'] = 'off';
            $this->data['customLimit'] = (is_null($this->formData['customLimit'])) ? $this->data['customLimit'] : $this->formData['customLimit'];
        } else {
            $this->data['limit'] = 'on';
        }

    }

    public function deleteMany()
    {
        parent::delete();

        $data = [];
        $domainsName = $this->getRequestValue('massActions', []);

        foreach ($domainsName as $name) {
            $decoded = json_decode(base64_decode($name));
            $account = explode('@', $decoded->email);
            $data[$account[1]][] = $account[0];
        }
        $this->userApi->email->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('selectedEmailDeleted');
    }
}
