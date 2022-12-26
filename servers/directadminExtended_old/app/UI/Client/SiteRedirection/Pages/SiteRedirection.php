<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SiteRedirection extends DataTableApi implements ClientArea
{
    protected $id    = 'siteRedirectionTable';
    protected $name  = 'siteRedirectionTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('local_url'))->setSearchable(true)->setOrderable())
            ->addColumn((new Column('type'))->setSearchable(true)->setOrderable())
            ->addColumn((new Column('to'))->setSearchable(true)->setOrderable());

    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\Delete())
            ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    public function replaceFieldDomain($colName, $row)
    {
        return $row['domain'] . $row['from'];
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data     = [
                'domain' => $domain
            ];
            $response = $this->userApi->domainForwarder->lists(new Models\Command\DomainForwarder($data));
            $array = $this->fetchData($response, $domain);
            $result = array_merge($result, $array);
        }

        $provider = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('domain', 'ASC');
        
        $this->setDataProvider($provider);
    }

    protected function fetchData($response, $domain)
    {
        $result = [];
        $itemArray = [];
        $resultArray = [];
        $iter = 0;
        foreach ($response as $key => $each)
        {
            foreach($each as $element => $value)
            {
                if($value->redirect_url)
                {
                    $result['domain'] = $domain;
                    $result['type'] = $value->type;
                    $result['to'] = $value->redirect_url;
                    $result['local_url'] = $value->local_url_path;
                    $result['id'] =  base64_encode(json_encode($result));

                    $itemArray[$iter] = $result;
                    $iter++;
                }
            }
            $resultArray = array_merge($resultArray, $itemArray);
        }
        return $resultArray;
    }
}
