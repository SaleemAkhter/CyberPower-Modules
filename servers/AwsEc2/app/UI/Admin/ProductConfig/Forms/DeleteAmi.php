<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers\AddImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class DeleteAmi extends BaseForm implements AdminArea
{
    protected $id = 'deleteAmi';
    protected $name = 'deleteAmi';
    protected $title = 'deleteAmiTitle';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new AddImage());

        $imageId = new Hidden('imageId');
        $this->addField($imageId);

        $this->setConfirmMessage('deleteAmiConfirm', ['amiId' => $this->getRequestValue('actionElementId')]);

        $this->loadDataToForm();
    }
}
