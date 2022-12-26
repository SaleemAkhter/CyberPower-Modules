<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Fields\ImageSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Reinstall;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps;

/**
 * Description of ReinstallAction
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ReinstallAction extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'reinstallActionForm';
    protected $name  = 'reinstallActionForm';
    protected $title = 'reinstallActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Reinstall());
        $this->initFields();
        $this->loadDataToForm();

        $this->setInternalAlertMessage('reinstallInfo');
        $this->setInternalAlertMessageType(AlertTypesConstants::DANGER);
    }

    public function initFields()
    {
        $this->addField((new ImageSelect("imageId")));
    }
}
