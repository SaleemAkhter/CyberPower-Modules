<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme\MassDeleteThemeForm;

class MassDeleteThemeModal extends ModalConfirmDanger implements AdminArea
{
    public function initContent()
    {
        $this->initIds('massDeleteThemeModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new MassDeleteThemeForm());
    }
}
