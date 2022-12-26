<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme\UpdateThemeForm;

class UpdateThemeModal extends BaseEditModal implements AdminArea
{
    public function initContent()
    {
        $this->initIds('updateThemeModal');
        $this->addForm(new UpdateThemeForm());
    }
}
