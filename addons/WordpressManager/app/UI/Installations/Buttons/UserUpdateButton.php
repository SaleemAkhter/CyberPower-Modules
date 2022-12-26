<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\App\UI\Installations\Modals\UserUpdateModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDatatableShowModal;

/**
 * Description of CreateButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserUpdateButton extends ButtonDatatableShowModal implements ClientArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->initIds('UserUpdateButton');
        $this->initLoadModalAction(new UserUpdateModal());
    }
}
