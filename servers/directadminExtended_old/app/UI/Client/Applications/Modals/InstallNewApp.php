<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ModalTabsEdit;

class InstallNewApp extends ModalTabsEdit implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    protected $id    = 'installNewAppModal';
    protected $name  = 'installNewAppModal';
    protected $title = 'installNewAppModal';

    public function initContent()
    {
        $this->loadLang();
        $indexParams = explode(',', sl('request')->get('index'));
        $this->addForm(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms\InstallNewApp())
            ->setRawTitle($this->lang->translate('installNewAppModal', 'modal', $this->id) . ' - ' . $indexParams[2] . ' ' . $indexParams[1]);
    }
}
