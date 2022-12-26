<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals\AddImageManually;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonCreate;

class AddAmiManually extends ButtonCreate implements AdminArea
{
    protected $id = 'addAmiManually';
    protected $name = 'addAmiManually';
    protected $title = 'addAmiManuallyTitle';

    public function initContent()
    {
        $this->initLoadModalAction(new AddImageManually());
    }
}
