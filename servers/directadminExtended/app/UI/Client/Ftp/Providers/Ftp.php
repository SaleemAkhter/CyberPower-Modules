<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ftp extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $elementId = json_decode(base64_decode($this->actionElementId));

        $this->data['login'] = $elementId->user;
        $this->data['isSuspended'] = $elementId->suspended;
    }

    public function create()
    {
        parent::create();

        $data = [
            'domain'    => $this->formData['domain'],
            'user'      => $this->formData['name'],
            'password'  => $this->formData['password'],
            'type'      => $this->formData['directory'],
            'path'      => $this->formData['customDirectory']
        ];
        $this->userApi->ftp->create(new Models\Command\Ftp($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('ftpAccountHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $loginExplode   = explode('@', $this->formData['login']);
        $data           = [
            'user'   => $loginExplode[0],
            'domain' => $loginExplode[1]
        ];
        $this->userApi->ftp->delete(new Models\Command\Ftp($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('ftpAccountHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $data = [
            'domain'    => $this->formData['domain'],
            'user'      => $this->formData['name'],
            'password'  => $this->formData['password'],
            'type'      => $this->formData['directory'],
            'path'      => $this->formData['customDirectory']
        ];
        $this->userApi->ftp->modify(new Models\Command\Ftp($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('ftpAccountPasswordChanged');
    }

    public function suspendMany()
    {
        parent::suspendUnsuspend();

        $data        = [];
        $massData = $this->getRequestValue('massActions', []);

        foreach ($massData as $name)
        {
            $encodedItems[] = json_decode(base64_decode($name));
        }
        $this->loadLang();
        foreach($encodedItems as $item)
        {
            if(!strpos($item->user, '@'))
            {
                return (new ResponseTemplates\RawDataJsonResponse())
                    ->setStatusError()
                    ->setMessage($this->lang->addReplacementConstant('domain',$item->user)->absoluteTranslate('domainCannotBeSuspended'));
            }

            $account = explode('@', $item->user);

            $data[$account[1]][] = $account[0];

        }

        $this->userApi->ftp->suspendMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleFtpSuspend');
    }

    public function unsuspendMany()
    {
        parent::suspendUnsuspend();

        $data        = [];
        $massData = $this->getRequestValue('massActions', []);

        foreach ($massData as $name)
        {
            $encodedItems[] = json_decode(base64_decode($name));
        }
        $this->loadLang();

        foreach ($encodedItems as $item) {
            if(!strpos($item->user, '@'))
            {
                return (new ResponseTemplates\RawDataJsonResponse())
                    ->setStatusError()
                    ->setMessage($this->lang->addReplacementConstant('domain',$item->user)->absoluteTranslate('domainCannotBeUnsuspended'));
            }
            $account = explode('@', $item->user);

            $data[$account[1]][] = $account[0];
        }
        $this->userApi->ftp->unsuspendMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleFtpUnsuspend');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $massData = $this->getRequestValue('massActions', []);

        foreach ($massData as $name)
        {
            $encodedItems[] = json_decode(base64_decode($name));
        }

        $this->loadLang();
        foreach ($encodedItems as $name) {
            if (!strpos($name->user, '@')) {
                return (new ResponseTemplates\RawDataJsonResponse())
                    ->setStatusError()
                    ->setMessage($this->lang->addReplacementConstant('domain', $name->user)->absoluteTranslate('domainCannotBeDeleted'));
            }
            $account = explode('@', $name->user);

            $data[$account[1]][] = $account[0];
        }

        $this->userApi->ftp->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('domainDeleted');
    }

    public function toggleSingleSuspend()
    {
        parent::suspendUnsuspend();

        $account = explode('@',  $this->formData['login']);
        $data[$account[1]][] = $account[0];

        if($this->formData['isSuspended'] === 'no')
        {
            // suspend
            $this->userApi->ftp->suspendMany($data);
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('singleFtpSuspend');
        }
        // unsuspend
        $this->userApi->ftp->unsuspendMany($data);
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('singleFtpUnsuspend');
    }
}
