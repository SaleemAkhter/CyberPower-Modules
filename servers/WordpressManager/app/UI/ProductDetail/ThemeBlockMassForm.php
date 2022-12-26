<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;

class ThemeBlockMassForm extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['createMass'];
    }

    public function initContent()
    {
        $this->initIds('ThemeBlockMassForm');
        $this->setFormType('createMass');
        $this->setProvider( new ThemeBlockedProvider);
        $this->initFields();
    }

    protected function initFields()
    {

        $this->setConfirmMessage('confirmThemeBlockMass', ['title' => null]);
    }
}
