<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated\Reinstall;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Reboot
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
        $this->setInternalAlertMessage('reinstallInfo');
        $this->setInternalAlertMessageType(AlertTypesConstants::DANGER);

        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Reinstall());
        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField((new Select('osTemplate'))
            ->addHtmlAttribute('bi-event-change', "initReloadModal"));
        $this->addField(new Select('language'));

    }

    public function reloadFormStructure()
    {
        $this->loadDataToForm();
    }


}
