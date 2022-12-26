<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\UserCreateForm;

/**
 * Description of CreateModal
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class UserCreateModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'userCreateModal';
    protected $name  = 'userCreateModal';
    protected $title = 'userCreateModal';

    public function initContent()
    {
        $this->addForm(new UserCreateForm());
    }
}
