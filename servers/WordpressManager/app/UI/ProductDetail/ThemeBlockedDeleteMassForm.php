<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;

class ThemeBlockedDeleteMassForm extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['deleteMass'];
    }

    public function initContent()
    {
        $this->initIds('themeBlockedDeleteMassForm');
        $this->setFormType('deleteMass');
        $this->setProvider( new ThemeBlockedProvider);
        $this->initFields();
    }

    protected function initFields()
    {

        $this->setConfirmMessage('confirmThemeBlockedMass', ['title' => null]);
    }
}
