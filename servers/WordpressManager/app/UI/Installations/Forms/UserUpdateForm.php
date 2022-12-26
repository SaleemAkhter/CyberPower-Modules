<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\UserProvider;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

class UserUpdateForm extends BaseForm implements ClientArea
{
    protected function getDefaultActions()
    {
        return ['update'];
    }

    public function initContent()
    {   
        $this->initIds('UserUpdateForm');
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new UserProvider);
        $this->initFields();
    }

    protected function initFields()
    {
        $roles = new Fields\Select('roles');
        $roles->setAvailableValues([
            'administrator' => 'administrator',
            'author'        => 'author',
            'contributor'   => 'contributor',
            'editor'        => 'editor',
            'subscriber'    => 'subscriber',
        ]);
        
        $this->addField(new Fields\Hidden('id'));
        $this->addField((new Fields\Text('login'))->disableField());
        $this->addField($roles);
        $this->addField(new Fields\Text('displayName'));
        $this->addField((new Fields\Text('email'))->notEmpty());

        $this->loadDataToForm();
    }
}