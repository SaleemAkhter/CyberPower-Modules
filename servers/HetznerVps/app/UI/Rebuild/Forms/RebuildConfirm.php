<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Forms;

use ModulesGarden\Servers\HetznerVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebuildConfirm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'rebuildConfirmForm';
    protected $name  = 'rebuildConfirmForm';
    protected $title = 'rebuildConfirmForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Providers\Rebuild());
        $this->setInternalAlertMessage('rebuildConfirm');
        $this->setInternalAlertMessageType(AlertTypesConstants::DANGER);
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->loadDataToForm();
    }

}
