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

namespace ModulesGarden\WordpressManager\App\UI\Products\Modals;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\UI\Products\Forms\MassEnableForm;

/**
 * Description of MassModal
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class MassEnableModal extends BaseEditModal implements AdminArea
{

    public function initContent()
    {
        $this->initIds('massEnableModal');
        $this->addForm(new MassEnableForm());
    }
}
