<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Providers\Shutdown;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ShutdownAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'shutdownActionForm';
    protected $name  = 'shutdownActionForm';
    protected $title = 'shutdownActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Shutdown());
        $this->setConfirmMessage('confirmShutodown');
        $this->loadDataToForm();
    }

}
