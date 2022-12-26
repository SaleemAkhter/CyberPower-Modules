<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\CustomPluginInstallForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;

class CustomPluginInstallModal extends ModalConfirmSuccess implements ClientArea
{

    public function initContent()
    {
        $this->initIds('customPluginInstallModal');
        $this->addForm(new CustomPluginInstallForm());
    }
}
