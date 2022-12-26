<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\CreateSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\DeleteSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\EditSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\MassAction\DeleteSSHKeyMassButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\PutSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;


class AuthorizeSsh extends RawDataTableApi implements ClientArea
{
    protected $id    = 'authorizeSshTable';
    protected $name  = 'authorizeSshTable';
    protected $title = 'authorizeSshTableTitle';

    public function loadHtml()
    {
        $this->addColumn((new Column('fingerprint'))->setSearchable(true))
            ->addColumn((new Column('type'))->setSearchable(true))
            ->addColumn((new Column('keysize'))->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new CreateSSHButton())
            ->addActionButton(new EditSSHButton())
            ->addActionButton(new DeleteSSHButton())
            ->addMassActionButton(new DeleteSSHKeyMassButton());


        $this->addButton(new PutSSHButton());
    }

    public function loadData()
    {
        $this->loadUserApi();
        $response = $this->userApi->ssh->lists(new Models\Command\Ssh())->firstOfArray();

        $result = [];

        foreach ($response['authorizedKeys'] as  $key => $value) {
            $value['keyid'] = $key;
            $value['kind'] = 'authorized_keys';
            $value['id'] = json_encode($value);
            $result[] = $value;
        }

        $dataProvider = new ArrayDataProvider();

        $dataProvider->setData($result);
        $dataProvider->setDefaultSorting('keyid', 'ASC');

        $this->setDataProvider($dataProvider);
    }
}
