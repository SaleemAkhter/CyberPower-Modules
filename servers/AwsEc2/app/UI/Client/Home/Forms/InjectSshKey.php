<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;

class InjectSshKey extends BaseForm implements AdminArea, ClientArea
{
    protected $allowedActions = [FormConstants::INJECT];
    public function initContent()
    {
        $this->setFormType(FormConstants::INJECT);
        $this->setProvider(new ServiceActions());

        $username = new Text('username');
        $this->addField($username);
        $publicKey = (new Textarea('publicKey'));
        $this->addField($publicKey);
    }

}