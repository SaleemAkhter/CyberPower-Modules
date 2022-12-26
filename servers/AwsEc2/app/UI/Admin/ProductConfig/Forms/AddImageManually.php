<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers\AddImage;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class AddImageManually extends BaseForm implements AdminArea
{
    protected $id = 'addImageManuallyForm';
    protected $name = 'addImageManuallyForm';
    protected $title = 'addImageManuallyFormTitle';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new AddImage());

        $imageId = new Text('imageId');
        $imageId->notEmpty();
        $this->addField($imageId);

        $customImageDescription = new Text('customImageDescription');
        $customImageDescription->setDescription('customImageTooltip');
        $this->addField($customImageDescription);
    }
}
