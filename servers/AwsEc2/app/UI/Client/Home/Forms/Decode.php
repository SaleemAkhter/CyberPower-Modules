<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Textarea;

class Decode extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'decodeForm';
    protected $name = 'decodeForm';
    protected $title = 'decodeFormTitle';

    protected $allowedActions = ['decode'];

    public function initContent()
    {
        $this->setFormType('decode');
        $this->setProvider(new ServiceActions());

        $this->setInternalAlertMessage('getWindowsPasswordInfo');

        $privateKey = new Textarea('privateKey');
        $privateKey->notEmpty();

        $this->addField($privateKey);
    }
}
