<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\NetworkInterfaceProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class DeleteForm extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';


    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new NetworkInterfaceProvider());
        $this->setInternalAlertMessage('confirmDeleteRule')
            ->setInternalAlertMessageType(AlertTypesConstants::DANGER);

        $instanceName = new Hidden('instanceName');
        $this->addField($instanceName);

        $rule = new Hidden('rule');
        $this->addField($rule);

        $protocol = new Hidden('protocol');
        $this->addField($protocol);

        $portRange = new Hidden('portRange');
        $this->addField($portRange);

        $source = new Hidden('source');
        $this->addField($source);

        $this->loadDataToForm();
    }

}
