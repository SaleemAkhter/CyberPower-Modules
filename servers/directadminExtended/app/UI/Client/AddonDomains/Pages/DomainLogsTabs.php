<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-11
 * Time: 10:44
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\TabsWidget\TabsWidget;

class DomainLogsTabs extends TabsWidget implements ClientArea
{
    use Lang;

    protected $id    = 'logsTabs';
    protected $name  = 'logsTabs';
    protected $title = 'logsTabs';

    protected $tabs  = [
        ErrorLogs::class,
        UsageLogs::class
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