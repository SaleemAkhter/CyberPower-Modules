<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms\EditAmi;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;

class EditImage extends BaseEditModal implements AdminArea
{
    protected $id = 'editImageModal';
    protected $name = 'editImageModal';
    protected $title = 'editImageModalTitle';

    public function initContent()
    {
        $this->addForm(new EditAmi());
    }
}
