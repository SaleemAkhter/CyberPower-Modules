<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Forms;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class SuspendUnsuspend extends BaseForm implements ClientArea
{
    protected $id    = 'suspendForm';
    protected $name  = 'suspendForm';
    protected $title = 'suspendForm';

    public function getDefaultActions()
    {
        return ['suspendUnsuspend'];
    }

    public function initContent()
    {
        $this->setFormType('suspendUnsuspend')
            ->setProvider(new Providers\Users())
            ->setConfirmMessage('confirmUserSuspend');

        $domain = (new Hidden('domain'))
        ->setDefaultValue(sl('request')->get('actionElementId'));

        $this->addField($domain)
            ->loadDataToForm();
    }
}
