<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SubscriberList extends RawDataTableApi
{
    protected function getMailingLists()
    {
        $this->loadUserApi();

        $explodeList = explode('@', $this->getRequestValue('list'));
        $name        = $explodeList[0];
        $domain      = $explodeList[1];
        $data        = [
            'name'    => $name,
            'domain'  => $domain
        ];
        $result      = $this->userApi->mailingList->view(new Models\Command\MailingList($data))->toArray();

        return $result;
    }

    protected function generateArray($type)
    {
        $array = [];
        $data = $this->getMailingLists();

        foreach ($data as $key => $each)
        {
            $idData = ['id' => $each['email'], 'type' => $each['type']];
            $id = base64_encode(json_encode($idData));

                $array [$each['type']][] = [
                    'id' =>  $id,
                    'email' => $each['email'],
                    'type' => $each['type'],
                ];
        }

        if($array[$type] === null)
        {
            return [];
        }

        return $array[$type];
    }
}