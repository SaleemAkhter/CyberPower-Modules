<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstallCustomMassModal extends ModalConfirmSuccess implements ClientArea
{
    protected $id    = 'customThemeInstallMassModal';
    protected $name  = 'customThemeInstallMassModal';
    protected $title = 'customThemeInstallMassModal';

    public function initContent()
    {
        $this->initIds('customThemeInstallMassModal');
        $this->addForm(new InstallCustomMassForm());
    }
}
