<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Forms;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Providers;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new Providers\DnsRecords())
            ->setConfirmMessage('confirmDnsDelete');

        $name = new Hidden('name');
        $value = new Hidden('value');
        $type = new Hidden('type');

        $this->addField($name)
            ->addField($value)
            ->addField($type)
            ->loadDataToForm();
    }
}