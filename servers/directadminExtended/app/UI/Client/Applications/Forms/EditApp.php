<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestFormDataHandler;

class EditApp extends BaseForm implements ClientArea
{
    use RequestFormDataHandler;

    protected $id    = 'editAppForm';
    protected $name  = 'editAppForm';
    protected $title = 'editAppForm';

    public function initContent()
    {
        $appId = $this->request->query->get('actionElementId');

        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\ApplicationsEdit());

        $this->addField((new Text())->initIds('editDbName'));
        $this->addField((new Text())->initIds('editDbUser'));
        $this->addField((new Text())->initIds('editDbPassword'));
        $this->addField((new Text())->initIds('editDbHost'));

        if(strpos($appId, '26_') !== false) {
            $this->addField((new Switcher())->initIds('autoUpgradePlugins'));
            $this->addField((new Switcher())->initIds('autoUpgradeThemes'));
        }

        $this->addField((new Hidden('id')));

        $this->loadDataToForm();
    }

}