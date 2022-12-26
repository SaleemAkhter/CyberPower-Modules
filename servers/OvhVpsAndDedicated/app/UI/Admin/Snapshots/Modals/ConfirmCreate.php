<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms\AddSnapshot;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmCreate extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmModal';
    protected $name  = 'confirmModal';
    protected $title = 'confirmModal';

    public function initContent()
    {       
        $this->addForm(new AddSnapshot());
    }

}
