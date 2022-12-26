<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;


class ProtectedDirectories extends DataTableApi implements ClientArea
{

    protected $id           = 'protectedDirectories';
    protected $name         = 'protectedDirectories';
    protected $title        = 'protectedDirectories';

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('path'))
                ->setSearchable(true)
                ->setOrderable('ASC')
        );
    }

    public function initContent()
    {
        $this->addActionButton(new Buttons\Delete());
    }

    protected function loadData()
    {
        $this->loadUserApi();
        $result = $this->userApi->fileManager->protectedDirs($this->getRequestValue('domain'));

        $provider   = new ArrayDataProvider();

        if ($result != null) {
            foreach ($result as $key => $path) {

                $data[] = [
                    'id'  => $key,
                    'path' => $path,
                ];
            }

            $provider->setData($data);

            $provider->setDefaultSorting('id', 'ASC');

            $this->setDataProvider($provider);
        }
    }
}
