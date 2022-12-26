<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Providers;

class ResetDefault extends BaseForm implements ClientArea
{
    protected $id    = 'resetDefaultForm';
    protected $name  = 'resetDefaultForm';
    protected $title = 'resetDefaultForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::REORDER)
            ->setProvider(new Providers\DnsRecords())
            ->setConfirmMessage('confirmDnsReset');

        $domain = new Hidden('domain');

        $this->addField($domain)
            ->loadDataToForm();
    }
}
