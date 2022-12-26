<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme\CreateThemeForm;

class CreateThemeModal extends BaseEditModal implements AdminArea
{

    public function initContent()
    {
        $this->initIds('createThemeForm');
        $this->addForm(new CreateThemeForm);
    }
}
