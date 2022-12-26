<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\UserDeleteForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;

class UserDeleteModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'deleteUserModal';
    protected $name  = 'deleteUserModal';
    protected $title = 'deleteUserModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new UserDeleteForm());
    }
}