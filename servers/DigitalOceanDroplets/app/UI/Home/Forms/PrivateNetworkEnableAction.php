<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Providers\PrivateNetworkEnable;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PrivateNetworkEnableAction extends BaseForm implements AdminArea
{

    protected $id    = 'privateNetworkEnableActionForm';
    protected $name  = 'privateNetworkEnableActionForm';
    protected $title = 'privateNetworkEnableActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new PrivateNetworkEnable());
        $this->setConfirmMessage('conforimEnablePrivateNetwork');
        $this->loadDataToForm();
    }

}
