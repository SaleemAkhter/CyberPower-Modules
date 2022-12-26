<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Plugin\CreatePluginForm;

class CreatePluginModal extends BaseEditModal implements AdminArea
{

    public function initContent()
    {
        $this->initIds('createPluginForm');
        $this->addForm(new CreatePluginForm);
    }
}
