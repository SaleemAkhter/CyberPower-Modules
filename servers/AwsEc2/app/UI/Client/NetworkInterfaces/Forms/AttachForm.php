<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\AttachProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Readonlyfield;

class AttachForm extends BaseForm implements ClientArea
{
    protected $id    = 'attachForm';
    protected $name  = 'attachForm';
    protected $title = 'attachForm';


    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new AttachProvider());
        $this->setInternalAlertMessage('infoAttachNetworkInterfaceToInstance')
            ->setInternalAlertMessageType(AlertTypesConstants::INFO);


        $instanceName = new Hidden('actionElementId');
        $this->addField($instanceName);

        $rule = new Readonlyfield('interfaceid');
        $this->addField($rule);

        $this->loadDataToForm();
    }

}
