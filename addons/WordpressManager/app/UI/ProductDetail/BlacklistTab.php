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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer; 
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
/**
 * Description of BlacklistTab
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class BlacklistTab extends BaseContainer implements AdminArea
{
    protected $id   = 'blacklistTab';
    protected $name = 'blacklistTab';
    protected $title = 'blacklistTabTitle';
    
     public function initContent()
    {
            $this->addElement(new PluginBlockedDataTable(null));  
            $this->addElement(new PluginBlockDataTable(null));  

    }
    
            
    
}
