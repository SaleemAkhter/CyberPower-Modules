<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms\InjectSshKey;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;

class InjectSshKeyModal extends BaseEditModal implements AdminArea, ClientArea
{
    protected $id = 'injectSshKeyModal';
    protected $name = 'injectSshKeyModal';
    protected $title = 'injectSshKeyModalTitle';

    public function initContent()
    {
        $this->addForm(new InjectSshKey());
    }

}