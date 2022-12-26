<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

class EditLimits extends Add implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\EmailsEdit());

        $this->addAllEditFields();
    }

    protected function addAllEditFields()
    {
        $this->addSection($this->getAccountSection())
            ->addSection($this->getQuotaSection());

        $this->loadDataToForm();

        $this->reloadFormStructure();

        $this->loadDataToForm();
    }

    protected function getAccountSection()
    {
        $email = (new InputGroup('emailGroup'))
            ->addInputComponent((new InputGroupElements\Text('accountCopy'))->disableField())
            ->addInputAddon('@', false, '@')
            ->addInputComponent((new InputGroupElements\Select('domainsCopy'))->disableField())
            ->setDescription('')
            ->addField(new Hidden('domains'))
            ->addField(new Hidden('account'));

        return $email;
    }

    protected function reloadFormStructure()
    {
        $selectedQuota = ($this->getRequestValue('formData')['quota']) ?: $this->getFormData()['quota'];
        $selectedLimit = ($this->getRequestValue('formData')['limit']) ?: $this->getFormData()['limit'];

        if($selectedQuota === "off")
        {
            $this->addSectionAfter('quotaSection', $this->getCustomQuotaSection());
        }

        if($selectedLimit === "off")
        {
            $requestData = json_decode(base64_decode($this->getRequestValue('actionElementId')));

            if($requestData->globalLimit != 0)
            {
                $this->addSectionAfter('limitSection', $this->getCustomLimitSection($requestData->globalLimit));
            } else
            {
                $this->addSection($this->getLimitSection());
                $this->addSectionAfter('limitSection', $this->getCustomLimitSection(sl('lang')->absoluteT('unlimited')));
            }
        }

        $this->dataProvider->reload();
        $this->loadDataToForm();
    }

}
