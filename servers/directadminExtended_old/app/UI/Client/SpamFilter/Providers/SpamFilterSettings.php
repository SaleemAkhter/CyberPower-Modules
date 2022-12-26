<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SpamFilterSettings extends SpamFilter
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        if ($this->getRequestValue('index') === 'settingsForm')
        {
            return;
        }
        $this->loadLang();
        parent::loadUserApi();

        $domains = $this->getDomainList();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $domains;

        $this->data['adult']                = [];
        $this->availableValues['adult']     = [
            'OFF'   => $this->lang->absoluteTranslate('disabled'),
            'ON'    => $this->lang->absoluteTranslate('enabled')
        ];

        $this->data['filterOptions']                = [];
        $this->availableValues['filterOptions']     = [
            'drop'   => $this->lang->absoluteTranslate('spamActionDrop'),
            'email'  => $this->lang->absoluteTranslate('spamActionEmail')
        ];

        $data     = [
            'domain' => $this->formData['domains'] ? $this->formData['domains'] : reset($domains)
        ];
        $response = $this->userApi->emailFilter->lists(new Models\Command\EmailFilter($data));

        $this->data['adult']            = $response->getAdult();
        $this->data['filterOptions']    = $response->getAction();

        if ($this->formData['domains'])
        {
            $this->data['domains']   = $this->formData['domains'];
        }
    }

    public function reload()
    {
        parent::loadUserApi();
        $data     = [
            'domain' => $this->formData['domains']
        ];
        $response = $this->userApi->emailFilter->lists(new Models\Command\EmailFilter($data));

        $this->data['adult']            = $response->getAdult();
        $this->data['filterOptions']    = $response->getAction();

        if ($this->formData['domains'])
        {
            $this->data['domains']   = $this->formData['domains'];
        }

    }

}
