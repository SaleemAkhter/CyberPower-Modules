<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;


class DomainsTable extends DataTableApi implements ClientArea
{

    protected $id           = 'domainsTable';
    protected $name         = 'domainsTable';
    protected $title        = 'domainsTable';

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('domain'))
                ->setSearchable(true)
                ->setOrderable('ASC')
        );
    }

    public function initContent()
    {
        $this->addActionButton($this->getRedirectButton());
    }

    public function getRedirectButton()
    {
        $button = new ButtonRedirect('folders');

        $button
            ->setIcon('lu-zmdi lu-zmdi-folder')
            ->setRedirectParams([
                'domain' => ':domain'
            ])
            ->setRawUrl(BuildUrl::getUrl('ProtectedDirectories', 'protectedDirectories', [], false));

        return $button;
    }

    protected function loadData()
    {
        $result = [];

        foreach ($this->getDomainList() as $item => $value) {
            $result[]['domain'] = $value;
        }

        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('domain', 'ASC');

        $this->setDataProvider($provider);
    }
}
