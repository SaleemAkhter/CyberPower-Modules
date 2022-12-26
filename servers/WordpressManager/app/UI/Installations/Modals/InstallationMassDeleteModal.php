<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationMassDeleteForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;

class InstallationMassDeleteModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->initIds('installationMassDeleteButton');
        $this->addForm(new InstallationMassDeleteForm());
    }
}