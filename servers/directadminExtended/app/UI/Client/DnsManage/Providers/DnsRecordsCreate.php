<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Providers;


class DnsRecordsCreate extends DnsRecords
{
    public function read()
    {
        $this->data['typeAdd']              = [];
        $this->availableValues['typeAdd']   = [
            'a' => 'A',
            'ns' => 'NS',
            'mx' => 'MX',
            'txt' => 'TXT',
            'cname' => 'CNAME',
            'ptr' => 'PTR',
            'srv' => 'SRV',
            'aaaa' => 'AAAA'];
    }

    public function reload()
    {
        $this->data['typeAdd']               = (is_null($this->formData['typeAdd'])) ? $this->data['typeAdd'] : $this->formData['typeAdd'] ;
        $this->data['name']                  = (is_null($this->formData['name'])) ? $this->data['name'] : $this->formData['name'];
        $this->data['value']                 = (is_null($this->formData['value'])) ? $this->data['value'] : $this->formData['value'];
    }
}