<?php
namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals\ConfirmDelete;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Delete extends ButtonDataTableModalAction implements ClientArea
{

    protected $id             = 'deleteButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center'];
    protected $icon           = 'lu-btn__icon lu-zmdi lu-zmdi-delete';  // cion
    protected $title          = 'deleteButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmDelete());
    }

}
