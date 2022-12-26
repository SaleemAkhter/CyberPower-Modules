<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Text;

class ResetPassword extends BaseForm implements ClientArea
{
    protected $id = 'resetPasswordForm';
    protected $name = 'resetPasswordForm';
    protected $title = 'resetPasswordForm';
    protected $class = ['hidden resetPassForm'];

    protected $allowedActions = ['resetPassword'];

    public function initContent()
    {
        $this->setFormType('resetPassword');
        $this->setProvider(new ServiceActions());

        $this->addField((new Text())->setPlaceholder('')->disableField()->setTitle('NewPassword'));

        $this->setConfirmMessage('confirmResetPassword');
    }
}