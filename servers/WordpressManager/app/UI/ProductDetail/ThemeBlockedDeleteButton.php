<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class ThemeBlockedDeleteButton extends ButtonDataTableModalAction implements AdminArea
{
    public function initContent()
    {
        $this->switchToRemoveBtn();
        
        $this->initLoadModalAction(new ThemeBlockedDeleteModal());
    }
}

