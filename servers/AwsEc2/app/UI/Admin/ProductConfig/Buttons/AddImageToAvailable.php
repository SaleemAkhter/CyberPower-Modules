<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals\AddImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class AddImageToAvailable extends ButtonDataTableModalAction implements AdminArea
{
    protected $id = 'addImageToAvailable';
    protected $name = 'addImageToAvailable';
    protected $title = 'addImageToAvailableTitle';

    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initLoadModalAction(new AddImage());
    }
}