<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class FtpCreate extends Ftp
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $this->loadLang();
        $this->data['domain']             = [];
        $this->availableValues['domain']  = $this->getDomainList();

        $this->data['directory']              = [];
        $this->availableValues['directory']   = [
            'domain'    => $this->lang->absoluteTranslate('domain'),
            'ftp'       => $this->lang->absoluteTranslate('ftp'),
            'user'      => $this->lang->absoluteTranslate('user'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        if ($this->formData['directory'])
        {
            $this->data['name']                 = $this->formData['name'];
            $this->data['domain']               = $this->formData['domain'];
            $this->data['password']             = $this->formData['password'];
            $this->data['directory']            = $this->formData['directory'];
        }

        $this->data['customDirectory']      = '/home/'.$this->getWhmcsParamByKey('username');
    }

    public function reload()
    {
        $this->data['domain']               = (is_null($this->formData['domain'])) ? $this->data['domain'] : $this->formData['domain'] ;
        $this->data['customDirectory']      = (is_null($this->formData['customDirectory'])) ? $this->data['customDirectory'] : $this->formData['customDirectory'];
        $this->data['directory']            = (is_null($this->formData['directory'])) ? $this->data['directory'] : $this->formData['directory'];
        $this->data['password']            = (is_null($this->formData['password'])) ? $this->data['password'] : $this->formData['password'];


    }
}
