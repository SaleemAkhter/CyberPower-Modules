<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons\MassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class BackupsTable extends RawDataTableApi implements ClientArea
{
    protected $id    = 'backupsTable';
    protected $name  = 'backupsTable';
    protected $title = 'backupsTableTab';

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add());
        $this->addActionButton(new Buttons\Restore());
        $this->addActionButton(new Buttons\Delete());
        $this->addMassActionButton(new MassAction\Delete());
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
                $result   = $this->resellerApi->backup->lists(new Models\Command\Backup($data))->response;
             }else{
                $result=[];
             }

        }else{
            $this->loadUserApi();
            $data     = [
                'domain' => $this->getWhmcsParamByKey('domain')
            ];
            if($data['domain']){
                $result   = $this->userApi->backup->lists(new Models\Command\Backup($data))->response;
            }else{
                $result   = [];
            }


        }

        $resultArray = [];
        foreach($result as $singleLine){

            $resultArray[] = [
                'id' => $singleLine->file,
                'name' => $singleLine->file
            ];
        }
        $provider = new ArrayDataProvider();

        $provider->setData($resultArray);
        $provider->setDefaultSorting('id', 'ASC');

        $this->setDataProvider($provider);
    }
}
