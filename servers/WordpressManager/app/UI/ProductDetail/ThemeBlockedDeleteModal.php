<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class ThemeBlockedDeleteModal extends ModalConfirmDanger implements AdminArea
{

    public function initContent()
    {
        $this->initIds('themeBlockedDeleteModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new ThemeBlockedDeleteForm());
    }
}
