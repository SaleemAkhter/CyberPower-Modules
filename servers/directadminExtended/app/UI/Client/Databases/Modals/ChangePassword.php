<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class ChangePassword extends BaseEditModal implements ClientArea
{
    use RequestObjectHandler, Lang;
    protected $id = 'changePasswordModal';
    protected $name = 'changePasswordModal';
    protected $title = 'changePasswordModal';

    /**
     *
     */
    public function initContent()
    {
        $this->addForm(new Forms\ChangePassword());
        $this->loadLang();

        //$this->loadRequestObj();

        if ($this->getRequestValue('actionElementId')) {
            $this->addReplacement();
        }
    }

    public function addReplacement()
    {
        $index = explode(',', $this->getRequestValue('index'));
        if (is_array($index)) {
            $this->lang->addReplacementConstant('user', $index[0]);
            $this->lang->addReplacementConstant('database', $index[1]);
        }
    }


}