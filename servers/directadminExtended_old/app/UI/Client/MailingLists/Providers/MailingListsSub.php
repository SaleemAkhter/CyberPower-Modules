<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class MailingListsSub extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;
        if($this->actionElementId === 'digestSubscribers')
        {
            $this->data['type']              = 'digest';
        }
        else
        {
            $this->data['type']              = 'list';
        }
    }

    public function create()
    {
        parent::create();

        $explodeList = explode('@', $this->getRequestValue('list'));
        $data        = [
            'name'      => $explodeList[0],
            'domain'    => $explodeList[1],
            'email'     => $this->formData['email'],
            'type'      => $this->formData['type']
        ];
        $this->userApi->mailingList->add(new Models\Command\MailingList($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('subscriberHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $email = json_decode(base64_decode($this->formData['name'])) ;

        $explodeList = explode('@', $this->getRequestValue('list'));
        $data        = [
            'name'      => $explodeList[0],
            'domain'    => $explodeList[1],
            'email'     => $email->id
        ];
        if ($email->type === 'digest')
        {
            $this->userApi->mailingList->deleteSubscriberDigest(new Models\Command\MailingList($data));

            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('subscriberHasBeenDeleted');
        }
        $this->userApi->mailingList->deleteSubscriber(new Models\Command\MailingList($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('subscriberHasBeenDeleted');
    }

    public function update()
    {
        parent::update();


        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('emailHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);

        $domain = explode('@', $this->getRequestValue('list'));

        foreach ($domainsName as $name) {

            $result[] = json_decode(base64_decode($name),true) ;

            foreach($result as $key)
            {
                $data[] = new Models\Command\MailingList([
                    'email' => $key['id'],
                    'name' => $domain[0],
                    'domain' => $domain[1],
                    'type' => $key['type']
                ]);
            }
        }

        $this->userApi->mailingList->deleteSubMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListSubDeleted');
    }
    
}
