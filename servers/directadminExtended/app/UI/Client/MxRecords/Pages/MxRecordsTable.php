<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage as Model;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Buttons\Delete;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Buttons\Edit;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Buttons\Create;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Buttons\MassAction;

class MxRecordsTable extends DataTableApi implements ClientArea
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


        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount"){
            $this->loadResellerApi([],false);
             $domains=$this->resellerApi->domain->lists();
             $domainlist=$domains->getResponse();
             if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data     = [
                    'domain' => $domainname
                ];
                $records   = $this->resellerApi->dnsManage->listMxRecords(new Model($data));
             }else{
                $records=[];
             }
        }elseif($this->getWhmcsParamByKey('producttype')  == "server"){
            $this->loadUserApi();
            $domains=$this->userApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data     = [
                    'domain' => $domainname
                ];
                $records   = $this->userApi->dnsManage->listMxRecords(new Model($data));
            }else{
                $records=[];
            }
        }else{
            $domain = $this->getRequestValue('domain');

            $data = [
                'domain' => $domain
            ];

            $this->loadUserApi();

            $records = $this->userApi->dnsManage->listMxRecords(new Model($data));

        }
        $tableData=[];
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
