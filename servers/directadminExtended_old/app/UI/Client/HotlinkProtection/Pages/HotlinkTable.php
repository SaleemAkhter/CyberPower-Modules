<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;

class HotlinkTable extends DataTableApi implements ClientArea
{
    protected $id    = 'hotlinkTable';
    protected $name  = 'hotlinkTable';
    protected $title = 'hotlinkTable';


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
        $button = new ButtonRedirect('details');

        $button
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-info-outline')
            ->setRedirectParams([
                'domain' => ':domain'
            ])
            ->setRawUrl(BuildUrl::getUrl('HotlinkProtection', 'hotlinkProtection', [], false));

        return $button;
    }

    protected function loadData()
    {
        $result = [];
        $domainList = $this->getDomainList();

        if ($domainList != null) {
            foreach ($this->getDomainList() as $item => $value) {
                $result[]['domain'] = $value;
            }

            $provider   = new ArrayDataProvider();

            $provider->setData($result);
            $provider->setDefaultSorting('domain', 'ASC');

            $this->setDataProvider($provider);
        }
    }
}
