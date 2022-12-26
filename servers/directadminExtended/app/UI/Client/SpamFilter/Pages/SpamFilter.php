<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DataTableDropdownButtons;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Buttons;

class SpamFilter extends DataTableApi implements ClientArea
{

    use Lang;
    protected $id    = 'spamFilter';
    protected $name  = 'spamFilter';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('type'))->setOrderable('ASC')->setSearchable(true))
            ->addColumn(new Column('value'))
            ->addColumn((new Column('domain'))->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButtonToDropdown(new Buttons\Settings())
            ->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    public function replaceFieldType($key, $row)
    {
        return $this->lang->absoluteTranslate($row[$key]);
    }

    public function replaceFieldValue($key, $row)
    {
        if($row['type'] == "Size"){
            return $row[$key] . ' KB';
        }

        return $row[$key];

    }
    protected function loadData()
    {
        $this->loadLang();
        $this->loadUserApi();

        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data     = [
                'domain' => $domain
            ];
            $response = $this->userApi->emailFilter->lists(new Models\Command\EmailFilter($data))->toArray();

            foreach ($response as $key => $each)
            {
                $idData = ['id' => $each['id'], 'domain' => $domain];
                $id = base64_encode(json_encode($idData));
                $response[$key]['id'] = $id;
                $response[$key]['domain']= $domain;
            }

            $result = array_merge($result, $response);
        }

        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('type', 'ASC');
        
        $this->setDataProvider($provider);
    }


}
