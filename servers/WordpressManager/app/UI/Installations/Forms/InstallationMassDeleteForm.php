<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationMassDeleteProvider;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;

class InstallationMassDeleteForm extends  BaseForm implements  ClientArea
{

    public function getAllowedActions()
    {
        return ['deleteMass'];
    }

    public function initContent()
    {
        $this->initIds('installationMassDeleteForm');
        $this->setFormType("deleteMass");
        $this->setProvider(new InstallationMassDeleteProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $this->setConfirmMessage('confirmDelete', ['title' => null]);
        //Delete Directory
        $this->addField(new Switcher("directoryDelete"));
        //Delete Database with User
        $this->addField(new Switcher("databaseDelete"));
    }

}