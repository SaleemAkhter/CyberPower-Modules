<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers\AddImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class AddToAvailableImages extends BaseForm implements AdminArea
{
    protected $id = 'addToAvailableImages';
    protected $name = 'addToAvailableImages';
    protected $title = 'addToAvailableImagesTitle';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new AddImage());

        $imageId = new Hidden('imageId');
        $this->addField($imageId);

        $imageId = new Text('imageIdDisplay');
        $imageId->disableField();
        $this->addField($imageId);

        $customImageDescription = new Text('customImageDescription');
        $customImageDescription->setDescription('customImageTooltip');
        $this->addField($customImageDescription);

        $this->loadDataToForm();
    }
}
