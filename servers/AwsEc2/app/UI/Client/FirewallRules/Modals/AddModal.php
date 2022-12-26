<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Forms\AddForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;

class AddModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'addModal';
    protected $name  = 'addModal';
    protected $title = 'addModal';

    public function initContent()
    {
        $this->addForm(new AddForm());
    }

}