<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class FtpEdit extends Ftp
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        $this->data['domain']                = [];
        $this->availableValues['domain']     = $this->getDomainList();
        $this->availableValues['domainCopy']     = $this->getDomainList();

        $this->data['directory']             = [];
        $this->availableValues['directory']  = [
            'domain' => $this->lang->absoluteTranslate('domain'),
            'ftp' => $this->lang->absoluteTranslate('ftp'),
            'user' => $this->lang->absoluteTranslate('user'),
            'custom' => $this->lang->absoluteTranslate('custom')
        ];


        $this->loadUserApi();
        $params = explode(',', $this->getRequestValue('index'));
        $login = explode('@', $params[0]);
        $data = [
            'user' => ($login[0] === 'editForm') ? $this->formData['name'] : $login[0],
            'domain' => ($login[0] === 'editForm') ? $this->formData['domain'] : $login[1]
        ];

        $account = $this->userApi->ftp->show(new Models\Command\Ftp($data))->first();

        if ($this->formData['directory'] && $this->getRequestValue('index') !== 'edit') {
            $this->data['name'] = $this->formData['name'];
            $this->data['nameCopy'] = $this->formData['name'];

            $this->data['domain'] = $this->formData['domain'];
            $this->data['domainCopy'] = $this->formData['domain'];;
            $this->data['password'] = $this->formData['password'];
            $this->data['directory'] = $this->formData['directory'];

            $patch = $account->getPath();
            $this->data['customDirectory'] = $patch ? $patch : '/home/' . $this->getWhmcsParamByKey('username');
        } else {
            $this->data['name'] = $account->getUser();
            $this->data['nameCopy'] = $account->getUser();
            $this->data['domain'] = $account->getDomain();
            $this->data['domainCopy'] = $account->getDomain();
            $this->data['directory'] = $account->getType();
            $this->data['customDirectory'] = $account->getPath();
        }
    }

}
