<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction ;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class ThemeBlockButton extends ButtonDataTableModalAction  implements AdminArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initIds('themeBlockButton');
        $this->initLoadModalAction(new ThemeBlockModal());
    }
}
