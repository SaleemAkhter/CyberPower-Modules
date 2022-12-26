<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms\DeleteAmi;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmDanger;

class DeleteImage extends ModalConfirmDanger implements AdminArea
{
    protected $id = 'deleteImage';
    protected $name = 'deleteImage';
    protected $title = 'deleteImageTitle';

    public function initContent()
    {
        $this->addForm(new DeleteAmi());
    }
}