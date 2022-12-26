<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\UserResetPasswordForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;

class UserResetPasswordModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'resetModal';
    protected $name  = 'resetModal';
    protected $title = 'resetModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new UserResetPasswordForm());
    }
}