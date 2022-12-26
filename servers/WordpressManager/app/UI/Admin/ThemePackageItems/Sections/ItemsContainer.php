<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Sections;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\TabsWidget\TabsWidget;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
/**
 * Doe labels datatable controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ItemsContainer extends TabsWidget  implements AdminArea
{

    protected $id    = 'itemsContainer';
    protected $name  = 'itemsContainer';
    protected $title = null;
    

    public function initContent()
    {
        $this->addElement(new GeneralTab());
        $this->addElement(new ThemesTab);
    }
    
}
