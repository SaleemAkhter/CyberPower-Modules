<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 5, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\Installations\Modals\StagingModal;
/**
 * Description of InstallationStagingButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class StagingButton extends ButtonMassAction implements ClientArea
{
    use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarTrait;
    
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-forward';
    protected $title ='stagingButton';
    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
        'title'       => 'Staging',
    ];
        
    public function initContent()
    {
        $this->initIds('stagingButton');
        $this->initLoadModalAction(new StagingModal());
        $this->setDisableByColumnValue('staging', '1');
        $this->setShowByColumnValue('actionsstaging', '1');
   
    }
    
}
