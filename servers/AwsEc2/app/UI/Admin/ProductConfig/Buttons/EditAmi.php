<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals\EditImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class EditAmi extends ButtonDataTableModalAction implements AdminArea
{
    protected $id = 'editAmi';
    protected $name = 'editAmi';
    protected $title = 'editAmiTitle';

    public function initContent()
    {
        $this->initLoadModalAction(new EditImage());
    }
}
