<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Providers;


class DnsRecordsCreate extends DnsRecords
{
    protected $currentdomain;
    public function read()
    {

        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount"){
            $this->loadResellerApi([],false);
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $this->currentdomain=$domain->name;

            }else{
                $domain='';
            }
            $this->currentApi=$this->resellerApi;
        }else{
            $this->currentdomain = $this->getRequestValue('domain');
            $this->loadUserApi();
            $this->currentApi=$this->currentApi;
        }


        $this->data['typeAdd']              = [];
        $this->data['name']              =$this->currentdomain. ".";
        $this->availableValues['typeAdd']   = [

            'mx' => 'MX',
            ];
    }

    public function reload()
    {
        $this->data['typeAdd']               = (is_null($this->formData['typeAdd'])) ? $this->data['typeAdd'] : $this->formData['typeAdd'] ;
        $this->data['name']                  = (is_null($this->formData['name'])) ? $this->data['name'] : $this->formData['name'];
        $this->data['value']                 = (is_null($this->formData['value'])) ? $this->data['value'] : $this->formData['value'];
    }
}
