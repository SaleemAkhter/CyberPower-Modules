<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\Ip;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Delete
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Delete extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'restoreBackupDropletForm';
    protected $name  = 'restoreBackupDropletForm';
    protected $title = 'restoreBackupDropletForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Ip());
        $this->setConfirmMessage('confirmDelete');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
