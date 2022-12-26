<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers\Backup;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
/**
 * Class Usage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Usage extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'diskUsageForm';
    protected $name  = 'diskUsageForm';
    protected $title = 'diskUsageForm';

    public function initContent()
    {

//        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Disk());
        $this->setConfirmMessage('confirmRestoreBackup');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}