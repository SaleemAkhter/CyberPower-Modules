<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms\AddToAvailableImages;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;

class AddImage extends BaseEditModal implements AdminArea
{
    protected $id = 'addImageModal';
    protected $name = 'addImageModal';
    protected $title = 'addImageModalTitle';

    public function initContent()
    {
        $this->addForm(new AddToAvailableImages());
    }
}
