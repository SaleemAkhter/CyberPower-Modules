<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 19, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\PluginDeactivateModal;

/**
 * Description of CloneButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class PluginDeactivateButton extends ButtonDataTableModalAction implements ClientArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-close';

    public function initContent()
    {
        $this->initLoadModalAction(new PluginDeactivateModal());
        $this->setDisableByColumnValue('statusRaw', 'inactive');
        
        $this->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain']);
    }
}
