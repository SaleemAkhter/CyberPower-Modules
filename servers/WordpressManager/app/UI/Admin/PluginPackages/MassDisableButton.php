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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackages;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
/**
 * Description of MassDisableButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MassDisableButton extends ButtonMassAction implements AdminArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-close';
    
    public function initContent()
    {
        $this->initIds('massDisableButton');
        $this->initLoadModalAction(new MassDisableModal());

        $this->replaceClasses(['lu-btn lu-btn--link lu-btn--plain']);
    }
}

