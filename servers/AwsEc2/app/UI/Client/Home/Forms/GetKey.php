<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\isAdmin;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;


class GetKey extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'getKeyForm';
    protected $name = 'getKeyForm';
    protected $title = 'getKeyFormTitle';

    protected $allowedActions = [FormConstants::READ];

    public function initContent()
    {
        $this->setFormType(FormConstants::READ);
        $this->setProvider(new ServiceActions());
        $publicKey = (new Textarea('public_key'));
        $this->addField($publicKey);
        $this->dataProvider->read();
        if($this->dataProvider->getValueById('private_key') && !isAdmin()){
            $privateKey = (new Textarea('private_key'));
            $this->addField($privateKey);
            $this->setInternalAlertMessage('getSshKeyInfo');
            $this->setInternalAlertMessageType(AlertTypesConstants::WARNING);
            $this->removeField('download');
        }

        $this->loadDataToForm();
    }


}
