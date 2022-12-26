<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Providers\ReverseDNSProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class DeleteReverseDNSForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id               = 'deleteReverseDNSForm';
    protected $name             = 'deleteReverseDNSForm';
    protected $title            = 'deleteReverseDNSForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new ReverseDNSProvider())
            ->setConfirmMessage('confirmReverseDNSDelete');

        $ip = (new Hidden('ip'))->initIds('ip');

        $this->addField($ip);
        $this->loadDataToForm();

    }

}