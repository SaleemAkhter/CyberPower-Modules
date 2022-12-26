<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallCustomMassForm;

class InstallCustomMassModal extends ModalConfirmSuccess implements ClientArea
{
    protected $id    = 'customPluginInstallMassModal';
    protected $name  = 'customPluginInstallMassModal';
    protected $title = 'customPluginInstallMassModal';

    public function initContent()
    {
        $this->initIds('customPluginInstallMassModal');
        $this->addForm(new InstallCustomMassForm());
    }
}