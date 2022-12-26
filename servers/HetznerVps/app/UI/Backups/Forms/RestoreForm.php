<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Forms;

use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RestoreForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'restoreForm';
    protected $name  = 'restoreForm';
    protected $title = 'restoreForm';

    public function initContent()
    {
        $this->setFormType('restore');
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Backups\Providers\BackupsProvider());
        $this->loadProvider();
        Lang::getInstance()->addReplacementConstant('description' ,$this->dataProvider->getValueById('description'));
        $this->setInternalAlertMessage('restoreConfirm');
        $this->setInternalAlertMessageType(AlertTypesConstants::DANGER);
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('description'));

        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['restore'];
    }
}
