<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SpamFilter extends ProviderApi
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
        $this->data['domain'] = $this->getRequestValue('index');
    }

    public function create()
    {
        parent::create();

        $data = [
            'domain' => $this->formData['domains'],
            'value' => $this->formData['value'],
            'type' => $this->formData['filter']
        ];
        $this->userApi->emailFilter->add(new Models\Command\EmailFilter($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('spamFilterHasBeenAdded');
    }

    public function delete()
    {
        parent::delete();

        $params = json_decode(base64_decode($this->formData['id']));

        $data = [
            'id' => ($params->id == "") ? "0" : $params->id,
            'domain' => $params->domain
        ];

        $this->userApi->emailFilter->delete(new Models\Command\EmailFilter($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('spamFilterHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $dataAction = [
            'domain' => $this->formData['domains'],
            'value' => $this->formData['filterOptions']
        ];
        $this->userApi->emailFilter->action(new Models\Command\EmailFilter($dataAction));

        $dataAdult = [
            'domain' => $this->formData['domains']
        ];
        $this->userApi->emailFilter->adult(new Models\Command\EmailFilter($dataAdult));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('spamFilterHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $domainsName = $this->getRequestValue('massActions', []);

        $data = [];

        foreach ($domainsName as $name)
        {
            $result[] = json_decode(base64_decode($name), true);
        }

        foreach($result as $elem => $each)
        {
            $data[$each['domain']][] = $each['id'];
        }

        $this->userApi->emailFilter->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleSpamFiltersHaveBeenDeleted');
    }

}
