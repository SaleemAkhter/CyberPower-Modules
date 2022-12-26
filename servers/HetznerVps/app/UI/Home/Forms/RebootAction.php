<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Providers\Reboot;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebootAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'rebootActionForm';
    protected $name  = 'rebootActionForm';
    protected $title = 'rebootActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Reboot());
        $this->setConfirmMessage('confirmReboot');
        $this->loadDataToForm();
    }

}
