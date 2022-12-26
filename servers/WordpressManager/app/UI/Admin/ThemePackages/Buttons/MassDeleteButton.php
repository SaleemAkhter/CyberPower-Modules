<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons;

use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Modals\MassDeleteModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
/**
 * Description of MassDeleteButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MassDeleteButton extends ButtonMassAction implements AdminArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';
    
    public function initContent()
    {
        $this->initIds('massDeleteButton');
        $this->initLoadModalAction(new MassDeleteModal());
        
        $this->switchToRemoveBtn();
    }
}
