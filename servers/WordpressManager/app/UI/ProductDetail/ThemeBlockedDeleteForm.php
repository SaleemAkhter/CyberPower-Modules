<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;

class ThemeBlockedDeleteForm  extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('themeBlockedDeleteForm');
        $this->setFormType('delete');
        $this->setProvider( new ThemeBlockedProvider);
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        $this->setConfirmMessage('confirmThemeBlockedDelete', ['name' => null]);
        sl('lang')->addReplacementConstant('name', $this->dataProvider->getValueById('name'));
        $this->addField((new Hidden('name'))->setValue($this->dataProvider->getValueById('name')));
        $this->addField((new Hidden('id'))->setValue($this->dataProvider->getValueById('id')));
    }
}
