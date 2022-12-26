<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage as Model;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Buttons\Delete;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Buttons\Edit;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Buttons\Create;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Buttons\MassAction;

class DnsRecords extends DataTableApi implements ClientArea
{
    protected $id    = 'dnsRecordsTable';
    protected $name  = 'dnsRecordsTable';
    protected $title = 'dnsRecordsTable';


    protected function loadHtml()
    {
        $this->addColumn((new Column('type'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('name'))->setSearchable(true))
            ->addColumn((new Column('value'))->setSearchable(true));

    }

    public function initContent()
    {
        $this->addActionButton(new Edit())
            ->addActionButton(new Delete())
        ->addMassActionButton(new MassAction\Delete())
        ->addButton(new Create());
    }


    protected function loadData()
    {
        $domain = $this->getRequestValue('domain');

        $data = [
            'domain' => $domain
        ];

        $this->loadUserApi();

        $records = $this->userApi->dnsManage->listRecords(new Model($data));

        foreach($records->response as $elem => $record)
        {
            $tmp = [
                'type' => $record->getType(),
                'name' => $record->getName(),
                'value' => trim($record->getValue(), '"'),
                'ttl' => $record->getTtl()
            ];
            $tmp['id'] = base64_encode(json_encode($tmp));

            $tableData[] = $tmp;
        }
        $provider   = new ArrayDataProvider();

        $provider->setData($tableData);
        $provider->setDefaultSorting('type', 'ASC');

        $this->setDataProvider($provider);
    }

}