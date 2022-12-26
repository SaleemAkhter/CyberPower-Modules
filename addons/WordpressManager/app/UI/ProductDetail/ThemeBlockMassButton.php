<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;

class ThemeBlockMassButton extends  ButtonMassAction implements AdminArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';
    public function initContent()
    {     
        
        $this->initIds('ThemeBlockMassButton');
        $this->initLoadModalAction(new ThemeBlockMassModal());
    }
}
