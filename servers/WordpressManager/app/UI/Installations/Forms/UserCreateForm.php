<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\UserProvider;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Checkbox;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;

class UserCreateForm extends BaseForm implements ClientArea
{

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new UserProvider);
        $this->initFields();
    }

    protected function initFields()
    {
        $this->addField((new Fields\Text('login'))->notEmpty());
        $this->addField((new Fields\Text('email'))->notEmpty());
        $this->addField(new Fields\Text('displayName'));
        $this->addField(new Fields\Text('password'));
        $roles = new Fields\Select('roles');
        $roles->setAvailableValues([
            'administrator' => 'administrator',
            'author'        => 'author',
            'contributor'   => 'contributor',
            'editor'        => 'editor',
            'subscriber'    => 'subscriber',
        ]);
        $this->addField($roles);
        $this->addField(new Switcher('notify'));        
    }
}
