<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Email;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Providers\CatchEmails;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->addInternalAlert('description', AlertTypesConstants::INFO);

        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new CatchEmails());

        $option = new Select('option');
        $option->setDescription('optionDescription');

        $email = new Text('email');
        $email->addValidator(new Email());

        $domain = new Hidden('domain');

        $this
            ->addField($option)
            ->addField($email)
            ->addField($domain);

        $this->loadDataToForm();

        if($this->dataProvider->getValueById('privileged') == false)
        {
            $this->addInternalAlert('notPrivileged', AlertTypesConstants::DANGER);
            $option->disableField();
            $email->disableField();
        }
    }
}