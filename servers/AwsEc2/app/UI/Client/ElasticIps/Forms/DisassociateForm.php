<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Providers\ElasticIpsProvider;
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
            ->setProvider(new ElasticIpsProvider());
        $this->setInternalAlertMessage('confirmDisassociate')
            ->setInternalAlertMessageType(AlertTypesConstants::DANGER);

        $instanceName = new Hidden('name');
        $this->addField($instanceName);

        $rule = new Readonlyfield('ipv4address');
        $this->addField($rule);

        $protocol = new Hidden('allocationid');
        $this->addField($protocol);

        $portRange = new Hidden('privateip');
        $this->addField($portRange);

        $source = new Readonlyfield('instanceid');
        $this->addField($source);

        $source = new Readonlyfield('networkinterface');
        $this->addField($source);


        $source = new Hidden('associated');
        $this->addField($source);
        $source = new Hidden('associationId');
        $this->addField($source);

        $this->loadDataToForm();
    }

}
