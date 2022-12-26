<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\UserCreateModal;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\UserResetPasswordModal;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDatatableShowModal;

/**
 * Description of CreateButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserResetPasswordButton extends ButtonDatatableShowModal implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-time-restore-setting';

    public function initContent()
    {
        $this->initIds('UserResetPasswordButton');
        $this->initLoadModalAction(new UserResetPasswordModal());
    }
}
