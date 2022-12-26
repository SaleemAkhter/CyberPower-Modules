<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 7, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations;


use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use \ModulesGarden\WordpressManager\App\UI\Installations\Theme;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Sidebars\Actions;
use \ModulesGarden\WordpressManager\App\Helper\CustomSoftware\GetProductCustomSoftware;

/**
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ThemeContainer extends BaseContainer implements ClientArea
{
    protected $id    = 'theme-page';
    protected $name  = 'theme-page-name';
    protected $title = 'theme-page-title';
    protected $vueComponent = true;
    

    public function initContent()
    {
        //Sidebar
        sl('sidebar')->add( new Actions('actions')); 
        $this->addElement(new Theme\InstalledDataTable());  
        $this->addElement(new Theme\InstallDataTable());

        $customThemes = (new GetProductCustomSoftware($this->request->get('wpid')))->getCustomThemesId();
        if(!empty($customThemes))
        {
            $this->addElement(new Theme\InstallCustomDataTable());
        }
    }

}
