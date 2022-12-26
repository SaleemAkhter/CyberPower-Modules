<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Subdomains extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'name'   => $this->formData['name'],
            'domain' => $this->formData['domains']
        ];
        $this->userApi->subdomain->create(new Models\Command\Subdomain($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('subdomainHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $sub    = null;
        $domain = null;
        $name = json_decode(base64_decode($this->formData['name']));

        foreach ($this->getDomainList() as $eachDomain)
        {
            if (strpos($name->name, $eachDomain) !== false)
            {
                $sub    = str_replace('.' . $eachDomain, '', $name->name);
                $domain = $eachDomain;
            }
        }

        if (!$sub || !$domain)
        {
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('incorrectSubdomain');
        }
        $data = [
            'name'      => $sub,
            'domain'    => $domain
        ];
        $this->userApi->subdomain->delete(new Models\Command\Subdomain($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('subdomainHasBeenDeleted');
    }

    public function deleteMassive()
    {
        foreach ($this->getRequestValue('massActions', []) as $subdomain)
        {
            $this->formData['name'] = $subdomain;
            $this->delete();
        }
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('massSubdomainHasBeenDeleted');

    }

}
