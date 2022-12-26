<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms\Update as UpdateForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class Update extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'ipUpdateModal';
    protected $name  = 'ipUpdateModal';
    protected $title = 'ipUpdateModal';

    public function initContent()
    {
        $this->addForm(new UpdateForm());
    }

}
