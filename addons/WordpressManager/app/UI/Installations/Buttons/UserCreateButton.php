<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\UserCreateModal;

/**
 * Description of CreateButton
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserCreateButton extends ButtonCreate implements ClientArea
{

    public function initContent()
    {
        $this->initIds('userCreateButton');
        $this->initLoadModalAction(new UserCreateModal());
    }
}
