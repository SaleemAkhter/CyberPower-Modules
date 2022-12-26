<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\TabsWidget\TabsWidget;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
/**
 * Doe labels datatable controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DetailContainer extends TabsWidget  implements AdminArea
{

    protected $id    = 'detailContainer';
    protected $name  = 'detailContainer';
    protected $title = null;
    

    public function initContent()
    {
        $this->addElement(new GeneralTab);
        $this->addElement(new BlacklistTab);
        $this->addElement(new ThemesBlacklistTab);
        $this->addElement(new ClientOptionsTab);
    }
    
}
