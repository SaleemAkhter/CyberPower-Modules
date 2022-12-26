<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;

class ThemeBlockMassModal extends ModalConfirmSuccess implements AdminArea
{

    public function initContent()
    {
        $this->initIds('ThemeBlockMassModal');
        $this->addForm(new ThemeBlockMassForm());
    }
}
