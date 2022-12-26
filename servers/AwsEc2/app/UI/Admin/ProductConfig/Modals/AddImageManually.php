<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Modals;

use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Forms\AddImageManually as AddImageManuallyForm;

class AddImageManually extends BaseEditModal implements AdminArea
{
    protected $id = 'addImageManuallyModal';
    protected $name = 'addImageManuallyModal';
    protected $title = 'addImageManuallyModalTitle';

    public function initContent()
    {
        $this->addForm(new AddImageManuallyForm());
    }
}
