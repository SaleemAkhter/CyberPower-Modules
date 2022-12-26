<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated\IpmiAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class IpmiAccess extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id    = 'ipmiAccessModal';
    protected $name  = 'ipmiAccessModal';
    protected $title = 'ipmiAccessModal';

    public function initContent()
    {
        $this->addForm(new IpmiAction());

        $this->initActionButtons();
        $this->actionButtons['baseAcceptButton']->addHtmlAttribute('@click', 'initReloadModal');

        if ($this->getRequestValue('mgformtype') === 'reload' && $this->getRequestValue('loadData') === 'ipmiButton')
        {
            unset($this->actionButtons['baseAcceptButton']);
        }
    }
}
