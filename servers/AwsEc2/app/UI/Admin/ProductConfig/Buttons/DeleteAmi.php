<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals\DeleteImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class DeleteAmi extends ButtonDataTableModalAction implements AdminArea
{
    protected $id = 'deleteAmi';
    protected $name = 'deleteAmi';
    protected $title = 'deleteAmiTitle';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->initLoadModalAction(new DeleteImage());
    }
}