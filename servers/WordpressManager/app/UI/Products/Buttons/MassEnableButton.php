<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 5, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Products\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\App\UI\Products\Modals\MassEnableModal;

/**
 * Description of MassButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class MassEnableButton extends ButtonMassAction implements AdminArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-check';
    
    public function initContent()
    {
        $this->initIds('massEnableButton');
        $this->initLoadModalAction(new MassEnableModal());
    }
}
