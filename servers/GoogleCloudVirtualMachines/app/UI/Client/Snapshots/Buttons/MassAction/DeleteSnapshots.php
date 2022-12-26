<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons\MassAction;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
/**
 * Description of DeleteSnapshot
 *
 * @author Kamil
 */
class DeleteSnapshots extends ButtonMassAction implements ClientArea
{
    
    protected $id    = 'deleteSnapshotButton';
    protected $name  = 'deleteSnapshotButton';
    protected $title = 'deleteSnapshotButton';
    protected $class          = ['lu-btn lu-btn--danger lu-btn--link lu-btn--plain lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center'];
    protected $icon           = 'lu-zmdi lu-zmdi-delete';  // cion
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
        'title' => 'Delete Snapshots'
    ];
    
     public function initContent()
    {
        
        $this->initLoadModalAction(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals\MassAction\DeleteSnapshots());
    }
      
    
}
