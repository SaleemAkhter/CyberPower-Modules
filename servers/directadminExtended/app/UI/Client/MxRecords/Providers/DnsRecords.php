<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class DnsRecords extends ProviderApi implements ClientArea
{
    protected $currentApi;
    protected $currentdomain;

    public function getResellerDomain(){
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
        }elseif($this->getWhmcsParamByKey('producttype')  == "server"){
            $this->loadUserApi([],false);
            $domains=$this->userApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $this->currentdomain=$domain->name;

            }else{
                $domain='';
            }
            $this->currentApi=$this->userApi;
        }else{
            $this->currentdomain = $this->getRequestValue('domain');
            $this->loadUserApi();
            $this->currentApi=$this->userApi;
        }
    }

    public function read()
    {
        $data = json_decode(base64_decode($this->actionElementId));
        $this->getResellerDomain();
        $this->data['type'] = $data->type;
        $this->data['typeTmp'] = $data->type;
        if(empty($data->name)){
            $this->data['name'] = $this->currentdomain.".";
        }else{
            $this->data['name'] = $data->name;
        }

        $this->data['value'] = trim($data->value, '"');
        $this->data['ttl'] = $data->ttl;
        $this->data['oldValue'] = $data->value;
        $this->data['oldName'] = $data->name;

        if($data->type == 'MX')
        {
            $value = explode(' ', $data->value);
            $this->data['mxNumeric'] = $value[0];
            $this->data['mxValue'] = $value[1];
        }
    }
    public function create()
    {
        parent::create();
        $this->getResellerDomain();
        $tmp = [];

        if($this->formData['txtValue'])
        {
            $value = $this->formData['txtValue'];
        } elseif($this->formData['mxNumeric'] !== null)
        {
            $value = $this->formData['mxNumeric'];
            $tmp['mxValue'] = $this->formData['mxValue'];
        }
        else
        {
            $value = $this->formData['value'];
        }

        $data = [
            'domain' => $this->currentdomain,
            'type' => $this->formData['typeAdd'],
            'name' => $this->formData['name'],
            'ttl' => $this->formData['ttl'],
            'value' => $value
        ];

        $data = array_merge($data, $tmp);

        $this->currentApi->dnsManage->createRecord(new DnsManage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mxRecordCreated');
    }
    public function delete()
    {
        parent::delete();
        $this->getResellerDomain();
        $this->formData['domain'] = $this->currentdomain;
        $this->currentApi->dnsManage->deleteRecord(new DnsManage($this->formData));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mxRecordDeleted');
    }
    public function update()
    {
        parent::update();
        $this->getResellerDomain();
        $oldValue = $this->formData['typeTmp'] == 'TXT' ? '"' . $this->formData['oldValue'] . '"' : $this->formData['oldValue'];

        $data = [
            'ttl' => $this->formData['ttl'],
            'value' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxNumeric'] : $this->formData['value'],
            'mxValue' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxValue'] : '',
            'name' => $this->formData['name'],
            'domain' => $this->currentdomain,
            'type' => $this->formData['typeTmp'],
            'oldValue' => $oldValue,
            'oldName' => $this->formData['oldName']
        ];

        $this->currentApi->dnsManage->editRecord(new DnsManage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mxRecordModified');
    }

    public function massDelete()
    {
        parent::delete();
        $data = [];
        $this->getResellerDomain();
        $dnsRecords = $this->getRequestValue('massActions', []);

        foreach ($dnsRecords as $key => $value) {

            $record =  json_decode(base64_decode($value));

            // if value contain space it must be surrounded with ""
            $data[$record->type][] = [
                'value' => $record->value,
                'name' => $record->name,
                'type' => $record->type,
                'domain' => $this->currentdomain
            ];

        }
        $this->currentApi->dnsManage->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mxRecordDeletedMany');
    }
}
