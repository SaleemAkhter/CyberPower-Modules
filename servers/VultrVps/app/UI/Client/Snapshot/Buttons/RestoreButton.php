<?php
/* * ********************************************************************
*  VultrVps Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Modals\RestoreModal;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class RestoreButton extends ButtonDataTableModalAction implements ClientArea
{
    protected $icon = 'lu-zmdi lu-zmdi-time-restore-setting';

    public function initContent()
    {
        $this->initIds('restoreButton');
        $this->initLoadModalAction(new RestoreModal());
    }

}