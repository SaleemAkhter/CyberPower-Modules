<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 26, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
/**
 * Description of PluginBlockedDeleteMassModal
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockedDeleteMassModal extends ModalConfirmDanger implements AdminArea
{

    public function initContent()
    {
        $this->initIds('pluginBlockedDeleteMassModal');
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new PluginBlockedDeleteMassForm());
    }
}
