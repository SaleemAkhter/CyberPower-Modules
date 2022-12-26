<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DataTableDropdownActionButtons;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MailingLists extends DataTableApi implements ClientArea
{
    use DataTableDropdownActionButtons;

    protected $id    = 'mailingLists';
    protected $name  = 'mailingLists';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('subscribers'))->setOrderable())
                ->addColumn((new Column('digestSubscribers'))->setOrderable());
    }

    public function initContent()
    {
        $view = new Buttons\View('view');
        $view->setRedirectParams(['list' => ':id'])
            ->setIcon('lu-zmdi lu-zmdi-edit')
            ->setRawUrl(BuildUrl::getUrl('MailingLists', 'edit'));

        $this->addButton(new Buttons\Add())
            ->addActionButton($view)
            ->addActionButton(new Buttons\Settings())
            ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
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
            $response = $this->userApi->mailingList->lists(new Models\Command\MailingList($data))->toArray();

            foreach ($response as $key => $each)
            {
                $response[$key]['id'] = $response[$key]['name'] . '@' . $domain;

            }
            $result   = array_merge($result,$response);
        }
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('id', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
