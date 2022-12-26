<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Modals;

use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\MaintenanceModeForm;

/**
 * Description of InstallationStagingModal
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MaintenanceModeModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'maintenanceModeModal';
    protected $name  = 'maintenanceModeModal';
    protected $title = 'maintenanceModeModal';

    public function initContent()
    {
        $this->initIds('maintenanceModeModal');
        $this->initActionButtons();
        $this->addForm(new MaintenanceModeForm($this));
    }
}
