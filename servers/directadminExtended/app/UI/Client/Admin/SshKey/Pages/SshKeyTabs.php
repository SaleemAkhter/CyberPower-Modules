<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\TabsWidget\TabsWidget;

class SshKeyTabs extends TabsWidget implements ClientArea
{
    use Lang;

    protected $id    = 'sshKeyTabs';
    protected $name  = 'sshKeyTabs';
    protected $title = 'sshKeyTabs';

    protected $tabs  = [
        SshKeyTable::class,
        AuthorizedKeysTable::class
    ];

    public function initContent()
    {

        $this->loadLang();

        $this->lang->addReplacementConstant('domain', $this->getRequestValue('domain', ''));
        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }

}
