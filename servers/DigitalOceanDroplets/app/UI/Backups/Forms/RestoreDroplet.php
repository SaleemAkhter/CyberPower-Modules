<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Providers\Backup;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RestoreDroplet extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'restoreBackupDropletForm';
    protected $name  = 'restoreBackupDropletForm';
    protected $title = 'restoreBackupDropletForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Backup());
        $this->setConfirmMessage('confirmRestoreBackup');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
