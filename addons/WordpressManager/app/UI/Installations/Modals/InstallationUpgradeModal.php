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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationUpgradeForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ModalActionButtons\BaseCancelButton;

/**
 * Description of CloneModal
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationUpgradeModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->initIds('installationUpgradeModal');
        $this->addForm((new InstallationUpgradeForm())->setModal($this));
    }
    
    public function initCloseButton(){
        $this->actionButtons=[];
        $this->addActionButton(new BaseCancelButton('close'));
    }
}
