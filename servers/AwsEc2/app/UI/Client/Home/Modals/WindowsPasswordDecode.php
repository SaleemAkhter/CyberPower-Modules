<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms\Decode;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;

class WindowsPasswordDecode extends BaseEditModal implements AdminArea, ClientArea
{
    protected $id = 'windowsPasswordDecode';
    protected $name = 'windowsPasswordDecode';
    protected $title = 'windowsPasswordDecodeTitle';

    public function initContent()
    {
        $this->addForm(new Decode());
    }
}