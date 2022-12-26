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

namespace ModulesGarden\WordpressManager\App\UI\Client\PluginPackages;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmSuccess;
/**
 * Description of MassInstallModal
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstallModal extends ModalConfirmSuccess implements ClientArea
{
    public function initContent()
    {
        $this->initIds('installModal');
        $this->addForm(new InstallForm());
    }
}
