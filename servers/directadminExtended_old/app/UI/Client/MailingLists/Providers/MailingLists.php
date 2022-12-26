<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class MailingLists extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

    }

    public function create()
    {
        parent::create();

        $data = [
            'name'      => $this->formData['name'],
            'domain'    => $this->formData['domains']
        ];
        $this->userApi->mailingList->create(new Models\Command\MailingList($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $explodeName = explode('@', $this->formData['name']);
        $data        = [
            'name'      => $explodeName[0],
            'domain'    => $explodeName[1]
        ];
        $this->userApi->mailingList->delete(new Models\Command\MailingList($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $explodeList = explode('@', $this->formData['name']);
        $data = [
            'name'   => $explodeList[0],
            'domain' => $explodeList[1]
        ];

        $result = $this->userApi->mailingList->modifyV2(new Models\Command\MailingList($data));

        $data = array_merge($data, $this->formData['options']);
        array_walk($data, function(&$item){
            $item = htmlspecialchars_decode($item);
        });
        /**
         * @var Models\Command\MailingList $result;
         */
        $result->fill($data);

        $this->userApi->mailingList->save($result);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);

        foreach ($domainsName as $name)
        {
            $account = explode('@', $name);

            $data[$account[1]][] = $account[0];
        }

        $this->userApi->mailingList->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListDeleted');
    }


    
}
