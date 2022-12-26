<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DomainPointers extends ProviderApi
{

    public function read()
    {
        $args                   = explode(',', $this->getRequestValue('index'));
        $this->data['name']     = $args[0];
        $this->data['domain']   = $args[1];
        $this->data['type'] = 'sdssd';
    }

    public function create()
    {
        parent::create();

        $data = [
            'from'      => $this->formData['name'],
            'domain'    => $this->formData['domains'],
            'alias'     => $this->formData['type'] == 'alias' ? 'yes' : 'no'
        ];
        $this->userApi->domainPointer->add(new Models\Command\DomainPointer($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('domainPointerHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'from'      => $this->formData['name'],
            'domain'    => $this->formData['domain'],
        ];
        $this->userApi->domainPointer->delete(new Models\Command\DomainPointer($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('domainPointerHasBeenDeleted');
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

        foreach ($encodedItems as $name) {

            $data[$name->domain][] = $name->source;
        }

        $this->userApi->domainPointer->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('domainPointersDeleted');
    }

}
