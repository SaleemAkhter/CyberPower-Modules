<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Nov 17, 2017)
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

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\App\UI\Products\Modals\EditModal;

/**
 * Description of ToggleButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @deprecated since version 1.2.0
 */
class EditButton extends ButtonModal implements AdminArea
{
    protected $id    = 'editButton';
    protected $name  = 'editButton';
    protected $title = 'editButton';
    protected $class = ['btn btn--sm btn--danger btn--link btn--icon btn--plain'];
    protected $icon  = 'btn__icon zmdi zmdi-edit';

    public function initContent()
    {
        $this->initLoadModalAction(new EditModal());
    }
}
