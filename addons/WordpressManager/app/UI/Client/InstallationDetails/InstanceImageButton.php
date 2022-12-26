<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 18, 2018)
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

/**
 * Description of InstanceImageButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageButton extends ButtonMassAction implements ClientArea
{
    use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarTrait;
    
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-group-work';

    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
        'title'       => 'Instance Image',
    ];

    public function initContent()
    {
        $this->initIds('instanceImageButton');
        $this->initLoadModalAction(new InstanceImageModal());
        $this->setShowByColumnValue('actionsinstanceimage', '1');
        return $this;
    }

}
