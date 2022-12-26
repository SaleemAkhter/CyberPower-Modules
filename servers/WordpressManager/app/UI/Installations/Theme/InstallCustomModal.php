<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstallCustomModal extends ModalConfirmSuccess implements ClientArea
{

    public function initContent()
    {
        $this->initIds('customThemeInstallModal');
        $this->addForm(new InstallCustomForm());
    }
}
