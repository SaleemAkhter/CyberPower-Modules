<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

/**
 * Class Vacation
 * @package ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers
 */
class Vacation extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;
    }

    /**
     * @return ResponseTemplates\HtmlDataJsonResponse|void
     */
    public function create()
    {
        parent::create();

        $explodeStartTime = explode('/', $this->formData['start']);
        $explodeEndTime   = explode('/', $this->formData['end']);

        $data = [
            'user'          => $this->formData['name'],
            'domain'        => $this->formData['domains'],
            'text'          => html_entity_decode($this->formData['message']),
            'starttime'     => $this->formData['starttime'],
            'startday'      => $explodeStartTime[0],
            'startmonth'    => $explodeStartTime[1],
            'startyear'     => $explodeStartTime[2],
            'endtime'       => $this->formData['endtime'],
            'endday'        => $explodeEndTime[0],
            'endmonth'      => $explodeEndTime[1],
            'endyear'       => $explodeEndTime[2]
        ];
        $this->userApi->vacation->create(new Models\Command\Vacation($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('messageHasBeenCreated');
    }

    /**
     * @return ResponseTemplates\HtmlDataJsonResponse|void
     */
    public function delete()
    {
        parent::delete();

        $explodeName = explode('@', $this->formData['name']);
        $data        = [
            'user'      => $explodeName[0],
            'domain'    => $explodeName[1]
        ];
        $this->userApi->vacation->delete(new Models\Command\Vacation($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('messageHasBeenDeleted');
    }

    /**
     * @return ResponseTemplates\HtmlDataJsonResponse|void
     */
    public function update()
    {
        parent::update();

        $explodeStartTime = explode('/', $this->formData['start']);
        $explodeEndTime   = explode('/', $this->formData['end']);

        $data = [
            'user'          => $this->formData['name'],
            'domain'        => $this->formData['domains'],
            'text'          => html_entity_decode($this->formData['message']),
            'starttime'     => $this->formData['starttime'],
            'startday'      => $explodeStartTime[0],
            'startmonth'    => $explodeStartTime[1],
            'startyear'     => $explodeStartTime[2],
            'endtime'       => $this->formData['endtime'],
            'endday'        => $explodeEndTime[0],
            'endmonth'      => $explodeEndTime[1],
            'endyear'       => $explodeEndTime[2]
        ];

        $this->userApi->vacation->modify(new Models\Command\Vacation($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('messageHasBeenUpdated');
    }


    protected function getMailUserList($domain){
        $this->loadUserApi();
        $accountList = [];

        $data     = [
            'domain' => $domain
        ];
        $list = $this->userApi->email->lists(new Models\Command\Email($data));

        if(!empty($list)){
            foreach($list->emails as $account){
                $accountList[$account->account] = $account->account;
            }
        }

        return $accountList;
    }

    public function deleteMany()
    {
        parent::delete();

        $data = [];
        $domainsName = $this->getRequestValue('massActions', []);

        foreach($domainsName as $key => $value)
        {
            $data[] = explode('@', $value);
        }

        foreach($data as $elem => $each)
        {
            $result[$each[1]][] = $each[0];
        }

        $this->userApi->vacation->deleteMany($result);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleMessagesHaveBeenDeleted');
    }
}
