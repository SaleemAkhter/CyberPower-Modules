<?php

/* * ********************************************************************
 * ProxmoxAddon product developed. (Aug 22, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\Jobs;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

/**
 * Description of UpdateButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InfoButton extends ButtonDataTableModalAction implements AdminArea
{
    protected $icon = 'lu-btn__con lu-zmdi lu-zmdi-info';

    public function initContent()
    {
        $this->initIds('infoButton');
        $this->initLoadModalAction(new InfoModal());
    }
}
