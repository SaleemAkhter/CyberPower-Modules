<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;

class ThemeBlockedDeleteMassButton extends  ButtonMassAction implements AdminArea
{
    public function initContent()
    {     
        $this->switchToRemoveBtn();
        
        $this->initIds('themeBlockedDeleteMassButton');
        $this->initLoadModalAction(new ThemeBlockedDeleteMassModal());
    }
}
