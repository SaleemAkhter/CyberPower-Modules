<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;

class ThemeBlockedDeleteMassModal extends ModalConfirmDanger implements AdminArea
{

    public function initContent()
    {
        $this->initIds('themeBlockedDeleteMassModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new ThemeBlockedDeleteMassForm());
    }
}
