<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Plugin\DeletePluginForm;

class DeletePluginModal extends ModalConfirmDanger implements AdminArea
{
    public function initContent()
    {
        $this->initIds('deletePluginModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new DeletePluginForm());
    }
}
