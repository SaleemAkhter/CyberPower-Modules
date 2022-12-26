<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class ThemeBlockModal extends ModalConfirmSuccess implements AdminArea
{
    public function initContent()
    {
        $this->initIds('themeBlockModal');
        $this->addForm(new ThemeBlockForm());
    }
}
