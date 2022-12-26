<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Others\InfoWidget;

class BlockedImages extends InfoWidget implements AdminArea
{
    protected $id = 'blockedImages';
    protected $name = 'blockedImages';
    protected $title = 'blockedImagesTitle';

    public function initContent()
    {
        $this->setMessage('imagesBlockedInfo');
    }
}
