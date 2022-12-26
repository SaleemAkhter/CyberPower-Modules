<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SiteRedirection extends ProviderApi
{

    public function read()
    {
        $this->data['idHidden'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'domain'    => $this->formData['domains'],
            'from'      => '/' .  $this->formData['from'],
            'to'        => $this->formData['protocol'] . $this->formData['destination'],
            'type'      => $this->formData['type']
        ];
        $this->userApi->domainForwarder->add(new Models\Command\DomainForwarder($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('redirectionHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'domain'    => $this->formData['domain'],
            'from'      => $this->formData['from']
        ];
        $this->userApi->domainForwarder->delete(new Models\Command\DomainForwarder($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('redirectionBeenDeleted');
    }

    public function massDelete()
    {
        parent::delete();

        $data = [];


        foreach ($this->getRequestValue('massActions', []) as $action)
        {
            $formData = json_decode(base64_decode($action));
            $data[$formData->domain][] = $formData->local_url;
        }
        $this->userApi->domainForwarder->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('massDatabaseRedirectionHasBeenDeleted');
    }


}
