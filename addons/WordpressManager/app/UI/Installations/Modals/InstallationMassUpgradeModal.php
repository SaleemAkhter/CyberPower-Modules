<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationMassUpgradeForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;

class InstallationMassUpgradeModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->initIds('installationMassUpgradeButton');
        $this->addForm(new InstallationMassUpgradeForm());
    }
}