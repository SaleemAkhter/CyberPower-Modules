<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends Edit implements ClientArea, AdminArea
{
    protected $id    = 'diskCreateForm';
    protected $name  = 'diskCreateForm';
    protected $title = 'diskCreateForm';

    public function initContent()
    {
        $this->setProvider(new Disk());
        $this->setFormType(FormConstants::CREATE);
        $this->initFields();
    }
}