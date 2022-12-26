<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 24, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Sections;

use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Pages\ItemsAddDataTable;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Pages\ItemsDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
/**
 * Description of PluginsTab
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ThemesTab extends BaseContainer implements AdminArea
{

    protected $id   = 'themesTab';
    protected $name = 'themesTab';
    protected $title = 'themesTabTitle';
    
     public function initContent()
    {
            $this->addElement(new ItemsDataTable(null));
            $this->addElement(new ItemsAddDataTable(null));

    }
    
            
    
}
