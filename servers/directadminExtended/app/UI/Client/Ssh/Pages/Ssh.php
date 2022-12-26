<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\CreateSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\DeleteSSHButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\MassAction\DeleteSSHKeyMassButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Pages\PublicSsh;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Pages\AuthorizeSsh;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;



class Ssh extends Tabs implements ClientArea
{
    protected $id    = 'sshPage';
    protected $name  = 'sshPage';
    protected $title = 'sshPage';

    protected $tabs  = [
        PublicSsh::class,
        AuthorizeSsh::class
    ];

    public function initContent()
    {
        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }
}