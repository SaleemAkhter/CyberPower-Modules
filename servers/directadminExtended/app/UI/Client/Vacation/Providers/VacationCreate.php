<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

/**
 * Class VacationCreate
 * @package ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers
 */
class VacationCreate extends Vacation
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        $this->buildDomainList();

        $domains = [];
        foreach ($this->domainList as $domain)
        {
            $domains[$domain] = $domain;
        }
        $this->data['domains']              = [];
        $this->availableValues['domains']   = $domains;
        $this->data['domains']              = ($this->formData['domains'])? $this->formData['domains'] : reset($domains);

        $this->data['name']                 = [];
        $this->availableValues['name']      =  $this->getMailUserList(($this->formData['domains'])? $this->formData['domains'] : reset($domains));

        $this->data['message'] = ($this->formData['message']) ? $this->formData['message'] : "";
        $this->data['start'] = ($this->formData['start']) ? $this->formData['start'] : "";
        $this->data['end'] = ($this->formData['end']) ? $this->formData['end'] : "";

        $this->data['starttime']                = [];
        $this->availableValues['starttime']     = [
            'morning'   => $this->lang->absoluteTranslate('morning'),
            'afternoon' => $this->lang->absoluteTranslate('afternoon'),
            'evening'   => $this->lang->absoluteTranslate('evening')
        ];
        $this->data['starttime'] = ($this->formData['starttime']) ? $this->formData['starttime'] : "";

        $this->data['endtime']              = [];
        $this->availableValues['endtime']   = [
            'morning'   => $this->lang->absoluteTranslate('morning'),
            'afternoon' => $this->lang->absoluteTranslate('afternoon'),
            'evening'   => $this->lang->absoluteTranslate('evening')
        ];
        $this->data['endtime'] = ($this->formData['endtime']) ? $this->formData['endtime'] : "";



    }


}
