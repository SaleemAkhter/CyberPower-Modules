<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Others\Label;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;

class AccountsTable extends DataTableApi implements ClientArea
{
    protected $id    = 'accountTable';
    protected $name  = 'accountTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('user'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('path'))->setSearchable(true))
            ->addColumn((new Column('suspended')));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
                ->addActionButton(new Buttons\Edit())
                ->addActionButton(new Buttons\Suspend())
                ->addActionButton(new Buttons\Delete())
                ->addMassActionButton(new Buttons\MassAction\Suspend())
                ->addMassActionButton(new Buttons\MassAction\Unsuspend())
                ->addMassActionButton(new Buttons\MassAction\Delete());
    }

//    public function replaceFieldUser($key, $row)
//    {
//        return str_replace('_', '.', $row['user']);
//    }

    public function replaceFieldSuspended($key, $row)
    {
        $status = strtolower($row[$key]);
        $labelClass = ($status == 'on' || $status == 'yes')? 'lu-label--danger' : 'lu-label--success';
        $label = new Label();
        $label->addClass($labelClass)
            ->addClass('lu-label--status')
            ->setBackgroundColor('')
            ->setColor('')
            ->setTitle(ServiceLocator::call('lang')->translate($status));

        return $label->getHtml();

    }

    protected function loadData()
    {
        $this->loadUserApi();
        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $accounts = $this->userApi->ftp->lists(new Models\Command\Ftp(['domain' => $domain]))->getResponse();

            foreach($accounts as $key => $account)
            {
                $account->suspended = $account->path->suspended;
                $account->path = $account->path->path;
                $account->id = base64_encode(json_encode($account));
                $result[] = $this->toArray($account);
            }
        }

        $result = $this->groupBy($result, 'user');

        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('user', 'ASC');

        $this->setDataProvider($provider);
    }

    protected function groupBy($data, $field)
    {
        $newData = [];
        $existedElements = [];
        foreach ($data as $row) {
            if (is_object($row)) {
                $id = $row->{$field};
            } else {
                $id = $row[$field];
            }

            if (in_array($id, $existedElements)) {
                continue;
            }

            $existedElements[] = $id;
            $newData[] = $this->toArray($row);
        }

        return $newData;
    }

    protected function toArray($object){
        $arrayElement = [];
        foreach($object as $key => $value){
            $arrayElement[$key] = $value;
        }

        return $arrayElement;
    }

}
