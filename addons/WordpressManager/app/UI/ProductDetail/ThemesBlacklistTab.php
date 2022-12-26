<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer; 
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class ThemesBlacklistTab extends BaseContainer implements AdminArea
{
    protected $id   = 'themesblacklistTab';
    protected $name = 'themesblacklistTab';
    protected $title = 'themesblacklistTabTitle';
    
     public function initContent()
    {
            $this->addElement(new ThemeBlockedDataTable(null));
            $this->addElement(new ThemeBlockDataTable(null));
    }
}
