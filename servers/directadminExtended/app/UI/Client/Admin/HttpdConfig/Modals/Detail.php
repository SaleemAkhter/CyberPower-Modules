<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Forms;

class Detail extends BaseEditModal implements ClientArea
{
    protected $id    = 'detailModal';
    protected $name  = 'detailModal';
    protected $title = 'detailModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm mg-submit-form hidden']);
        $this->addForm(new Forms\Detail());
        $this->setModalSizeLarge();

    }
}
