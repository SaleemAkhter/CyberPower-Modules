<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\AssociateIpProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Readonlyfield;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class DisassociateForm extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';


    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new AssociateIpProvider());
        $this->setInternalAlertMessage('confirmDisassociate')
            ->setInternalAlertMessageType(AlertTypesConstants::DANGER);

        // $this->dataProvider->read();
        // debug($this->dataProvider->getData());die();
        $instanceName = new Hidden('actionElementId');
        $this->addField($instanceName);
        $instanceName = new Hidden('name');
        $this->addField($instanceName);



        $source = new Readonlyfield('interfaceid');
        $this->addField($source);

        $rule = new Readonlyfield('publicip');
        $this->addField($rule);


        $source = new Hidden('associated');
        $this->addField($source);
        $source = new Hidden('associationId');
        $this->addField($source);

        $this->loadDataToForm();
    }

}
