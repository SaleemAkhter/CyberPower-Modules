<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\MaintenanceModeModal;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;

/**
 * Description of InstallationPushToLiveButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MaintenanceModeButton extends ButtonMassAction implements ClientArea
{
    use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarTrait;
    use BaseClientController;

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-wrench';
    protected $title = 'maintenanceMode';


    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
        'title'       => 'Maintenance Mode',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new MaintenanceModeModal());
        $this->setShowByColumnValue('actionsmaintenancemode', '1');
    }
}
