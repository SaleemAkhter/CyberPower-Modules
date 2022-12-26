<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\App\UI\Installations\Modals\UserDeleteModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of CreateButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserDeleteButton extends ButtonDataTableModalAction implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->initIds('userDeleteButton');
        $this->initLoadModalAction(new UserDeleteModal());
        $this->setDisableByColumnValue('id', '1');
        $this->switchToRemoveBtn();
    }
}
