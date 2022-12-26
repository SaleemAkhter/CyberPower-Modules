<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Providers\IPv6Enable;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class IPv6EnableAction extends BaseForm implements AdminArea
{

    protected $id    = 'iPv6EnableActionForm';
    protected $name  = 'iPv6EnableActionForm';
    protected $title = 'iPv6EnableActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new IPv6Enable());
        $this->setConfirmMessage('conforimEnableIPv6');
        $this->loadDataToForm();
    }

}
