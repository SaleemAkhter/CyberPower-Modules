<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Modals\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated\CreateConfigurableAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Modals\CreateConfigurableOptionsConfirm as DedicatedCreateConfigurableOptionsConfirm;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class CreateConfigurableOptionsConfirm extends DedicatedCreateConfigurableOptionsConfirm implements AdminArea
{
    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateConfigurableAction());
    }

}
