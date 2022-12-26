<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class DnsRecords extends ProviderApi implements ClientArea
{
    public function read()
    {
        $data = json_decode(base64_decode($this->actionElementId));

        $this->data['type'] = $data->type;
        $this->data['typeTmp'] = $data->type;
        $this->data['name'] = $data->name;
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
            'domain' => $this->getRequestValue('domain'),
            'type' => $this->formData['typeAdd'],
            'name' => $this->formData['name'],
            'value' => $value
        ];

        $data = array_merge($data, $tmp);

        $this->userApi->dnsManage->createRecord(new DnsManage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordCreated');
    }
    public function delete()
    {
        parent::delete();

        $this->formData['domain'] = $this->getRequestValue('domain');
        $this->userApi->dnsManage->deleteRecord(new DnsManage($this->formData));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordDeleted');
    }
    public function update()
    {
        parent::update();

        $oldValue = $this->formData['typeTmp'] == 'TXT' ? '"' . $this->formData['oldValue'] . '"' : $this->formData['oldValue'];

        $data = [
            'ttl' => $this->formData['ttl'],
            'value' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxNumeric'] : $this->formData['value'],
            'mxValue' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxValue'] : '',
            'name' => $this->formData['name'],
            'domain' => $this->getRequestValue('domain'),
            'type' => $this->formData['typeTmp'],
            'oldValue' => $oldValue,
            'oldName' => $this->formData['oldName']
        ];

        $this->userApi->dnsManage->editRecord(new DnsManage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordModified');
    }

    public function massDelete()
    {
        parent::delete();
        $data = [];

        $dnsRecords = $this->getRequestValue('massActions', []);

        foreach ($dnsRecords as $key => $value) {

            $record =  json_decode(base64_decode($value));

            // if value contain space it must be surrounded with ""
            $data[$record->type][] = [
                'value' => $record->value,
                'name' => $record->name,
                'type' => $record->type,
                'domain' => $this->getRequestValue('domain')
            ];

        }
        $this->userApi->dnsManage->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordDeletedMany');
    }
}