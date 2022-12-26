<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage as Model;

class DnsManage extends AbstractCommand
{

    const DNS_MANAGEMENT = 'CMD_DNS_CONTROL';


    public function listRecords(Model $data)
    {
        $requestData =
            [
                'json' => 'yes',
                'domain' => $data->getDomain(),
                'full_mx_records' => 'yes',
                'ttl' => 'yes'
            ];
        $response =   $this->curl->request(self::DNS_MANAGEMENT, [],$requestData);

        return $this->loadResponse(new Model, $response, __FUNCTION__);
    }

    public function createRecord(Model $data)
    {
        $requestData =
            [
                'json' => 'yes',
                'action' => 'add',
                'domain' => $data->getDomain(),
                'type' => strtoupper($data->getType()),
                'name' => $data->getName(),
                'value' => $data->getValue(),
                'mx_value' => $data->getMxValue()
            ];

        if($data->getType() == 'ns')
        {
            $requestData['name'] = $data->getValue();
            $requestData['value'] = $data->getName();
        }

        $this->curl->request(self::DNS_MANAGEMENT, $requestData);
    }

    public function editRecord(Model $data)
    {
        $requestData =
            [
                'json' => 'yes',
                'domain' => $data->getDomain(),
                'type' => $data->getType(),
                'name' => $data->getName(),
                'value' => $data->getValue(),
                'ttl' => $data->getTtl(),
                'action' => 'edit',
                strtolower($data->getType()) .'recs0' => 'name='.$data->getOldName().'&value=' . $data->getOldValue()
            ];

        if($data->getType() == 'MX')
        {
            $requestData['mx_value'] = $data->getMxValue();
        }
        elseif($data->getType() == 'NS')
        {
            // this record has reversed variables
            $requestData['name'] = $data->getValue();
            $requestData['value'] = $data->getName();
        }

        $this->curl->request(self::DNS_MANAGEMENT, $requestData);
    }

    public function deleteRecord(Model $data)
    {
        $requestData =
            [
                'json' => 'yes',
                'domain' => $data->getDomain(),
                'action' => 'select',
                'delete' => 'yes',
                strtolower($data->getType()) . 'recs0' => 'name='.$data->getName().'&value=' .$this->prepareValue($data->getType(), $data->getValue())
            ];

        $this->curl->request(self::DNS_MANAGEMENT, $requestData);
    }

    public function deleteMany($data)
    {
        $requestData = [];
        $domain = '';

        foreach ($data as $type => $record)
        {
            foreach($record as $elem => $value)
            {
                $requestData[strtolower($type) . 'recs' . $elem] = 'name=' . $value['name'] . '&value=' . $this->prepareValue($type, $value['value']);
                $domain = $value['domain'];
            }
        }

        $this->curl->request(self::DNS_MANAGEMENT, array_merge([
            'action'    => 'select',
            'delete'    => 'yes',
            'json'      => 'yes',
            'domain'    => $domain
        ], $requestData));
    }

    protected function prepareValue($type, $value)
    {
        $value = str_replace("\t", ' ', $value);

        if(strtolower($type) != 'txt')
        {
            return $value;
        }

        if(stripos($value, ' ') !== false || stripos($value, '"') !== false || stripos($value, "'") !== false)
        {
            return '"'.$value.'"';
        }

        return $value;
    }

}