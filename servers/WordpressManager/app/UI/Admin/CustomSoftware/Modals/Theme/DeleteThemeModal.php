<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme\DeleteThemeForm;

class DeleteThemeModal extends ModalConfirmDanger implements AdminArea
{
    public function initContent()
    {
        $this->initIds('deleteThemeModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new DeleteThemeForm());
    }
}
