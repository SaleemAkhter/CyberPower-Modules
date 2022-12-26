<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\MassAction;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseMassActionButton;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Modals;
/**
 * Description of DeleteSnapshot
 *
 * @author Kamil
 */
class DeleteSnapshots extends BaseMassActionButton implements ClientArea, AdminArea
{
    
    protected $id    = 'deleteSnapshotButton';
    protected $name  = 'deleteSnapshotButton';
    protected $title = 'deleteSnapshotButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link btn--icon lu-btn--plain lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center'];
    protected $icon           = 'lu-zmdi lu-zmdi-delete';  // cion
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
        'title' => 'Delete Snapshots'
    ];
    
     public function initContent()
    {
         
        $this->initLoadModalAction(new Modals\MassAction\DeleteSnapshots());
        
    }
      
    
}
