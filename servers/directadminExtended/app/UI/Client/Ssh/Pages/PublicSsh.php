<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\AuthorizeSSHKeyButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\CreateSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\DeleteSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\MassAction\AuthorizeSshKeysButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\MassAction\DeleteSSHKeyMassButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;


class PublicSsh extends RawDataTableApi implements ClientArea
{
    protected $id    = 'publicSshTable';
    protected $name  = 'publicSshTable';
    protected $title = 'publicSshTableTitle';

    public function loadHtml()
    {
        $this->addColumn((new Column('keyid'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('fingerprint'))->setSearchable(true))
            ->addColumn((new Column('type'))->setSearchable(true))
            ->addColumn((new Column('keysize'))->setSearchable(true))
            ->addColumn((new Column('authorized'))->setClass('hidden'));
    }

    public function initContent()
    {
        $this->addButton(new CreateSSHButton())
            ->addActionButton(new AuthorizeSSHKeyButton())
            ->addActionButton(new DeleteSSHButton())
            ->addMassActionButton(new DeleteSSHKeyMassButton())
            ->addMassActionButton(new AuthorizeSshKeysButton());
    }

    public function loadData()
    {
        $this->loadUserApi();
        $response = $this->userApi->ssh->lists(new Models\Command\Ssh())->firstOfArray();

        $result = [];

        foreach ($response['publicKeys'] as  $key => $value)
        {

            $value['authorized'] = isset($response['authorizedKeys'][$value['fingerprint']]) ? true : false;
            $value['keyid'] = $key;
            $value['kind'] = 'public';
            $value['id'] = json_encode($value);
            $result[] = $value;
        }

        $dataProvider = new ArrayDataProvider();

        $dataProvider->setData($result);
        $dataProvider->setDefaultSorting('keyid', 'ASC');

        $this->setDataProvider($dataProvider);


    }
}