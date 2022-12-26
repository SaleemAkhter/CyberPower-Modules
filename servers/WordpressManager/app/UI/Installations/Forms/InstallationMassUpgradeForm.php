<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;


use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationMassUpgradeProvider;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;

class InstallationMassUpgradeForm extends  BaseForm implements  ClientArea
{

    public function getAllowedActions()
    {
        return ['upgradeMass'];
    }

    public function initContent()
    {
        $this->initIds('installationMassUpgradeForm');
        $this->setFormType("upgradeMass");
        $this->setInternalAlertMessage('confirmMassUpgrade');
        $this->setProvider(new InstallationMassUpgradeProvider());
        $this->addField(new Switcher("backup"));
    }

}