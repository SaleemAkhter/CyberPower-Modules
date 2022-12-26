<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager\App\UI\Installations\Providers\UserProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;



class UserDeleteForm extends BaseForm implements ClientArea
{
    protected function getDefaultActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('userDeleteForm');
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new UserProvider);
        $this->initFields();
    }

    protected function initFields()
    {
        $this->addField(new Fields\Hidden('id'));
        $this->setConfirmMessage('confirmUserDelete', ['title' => null]);
        $this->loadDataToForm();
    }
}
