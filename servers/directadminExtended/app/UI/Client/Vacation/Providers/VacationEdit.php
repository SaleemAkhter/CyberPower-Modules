<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

/**
 * Class VacationEdit
 * @package ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers
 */
class VacationEdit extends VacationCreate
{

    public function read()
    {
        if ($this->getRequestValue('index') === 'editForm')
        {
            return;
        }

        $explodeName = explode('@', $this->actionElementId);
        $data        = [
            'user'   => $explodeName[0],
            'domain' => $explodeName[1]
        ];
        $this->formData['domains'] = $data['domain'];
        parent::read();
        $response = $this->userApi->vacation->view(new Models\Command\Vacation($data))->first();

        $this->availableValues['oldDomains']    = $this->availableValues['domains'];
        $this->availableValues['oldName']       = $this->availableValues['name'];

        if ($response)
        {
            $this->data['oldDomains']   = $data['domain'];
            $this->data['domains']      = $data['domain'];
            $this->data['oldName']      = $data['user'];
            $this->data['name']         = $data['user'];
            $this->data['starttime']    = $response->starttime;
            $this->data['start']        = $response->startday . '/' . $response->startmonth . '/' . $response->startyear;
            $this->data['endtime']      = $response->endtime;
            $this->data['end']          = $response->endday . '/' . $response->endmonth . '/' . $response->endyear;
            $this->data['message']      = html_entity_decode($response->getText());
        }

    }
}
