<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\App\UI\Installations\Forms\UserUpdateForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

/**
 * Description of CreateModal
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserUpdateModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'userUpdateModal';
    protected $name  = 'userUpdateModal';
    protected $title = 'userUpdateModal';

    public function initContent()
    {
        $this->addForm(new UserUpdateForm());
    }
}