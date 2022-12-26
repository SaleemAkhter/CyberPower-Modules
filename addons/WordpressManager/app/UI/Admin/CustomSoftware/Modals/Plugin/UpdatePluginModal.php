<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Plugin\UpdatePluginForm;

class UpdatePluginModal extends BaseEditModal implements AdminArea
{
    public function initContent()
    {
        $this->initIds('updatePluginModal');
        $this->addForm(new UpdatePluginForm());
    }
}
