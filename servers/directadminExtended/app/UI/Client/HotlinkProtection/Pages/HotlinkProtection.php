<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;

class HotlinkProtection extends DataTableApi implements ClientArea
{
    protected $id    = 'hotlinkProtection';
    protected $name  = 'hotlinkProtection';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('url'))
                ->setSearchable(true)
                ->setOrderable('ASC')
        );
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add)
            ->addButton(new Buttons\Settings)
            ->addActionButton(new Buttons\Delete);
    }

    protected function loadData()
    {
        $domain = $this->getRequestValue('domain');

        $this->loadUserApi();
        $result = $this->userApi->hotlinkProtection->lists($domain)->response;

        $provider   = new ArrayDataProvider();

        if ($result != null) {
            foreach ($result[0]->urls as $key => $object) {

                $data[] = [
                    'id'  => $key,
                    'url' => $object['url'],
                ];
            }

            array_pop($data);

            $provider->setData($data);

            $provider->setDefaultSorting('id', 'ASC');

            $this->setDataProvider($provider);
        }
    }
}
