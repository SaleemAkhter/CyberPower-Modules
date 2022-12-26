<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\NetworkInterfaceProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Readonlyfield;

class DeleteForm extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';


    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new NetworkInterfaceProvider());
        $this->setInternalAlertMessage('confirmDeleteNetworkInterface')
            ->setInternalAlertMessageType(AlertTypesConstants::DANGER);

        $instanceName = new Hidden('name');
        $this->addField($instanceName);
        $instanceName = new Hidden('subnet');
        $this->addField($instanceName);
        $instanceName = new Hidden('zone');
        $this->addField($instanceName);
        $instanceName = new Hidden('securitygroup');
        $this->addField($instanceName);
        $instanceName = new Hidden('InterfaceType');
        $this->addField($instanceName);
        $instanceName = new Hidden('publicip');
        $this->addField($instanceName);
        $instanceName = new Hidden('privateip');
        $this->addField($instanceName);


        $rule = new Readonlyfield('interfaceid');
        $this->addField($rule);

        $this->loadDataToForm();
    }

}
