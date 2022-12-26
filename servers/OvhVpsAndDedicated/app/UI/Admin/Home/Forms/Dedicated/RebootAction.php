<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated\Reboot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RebootAction extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'rebootActionForm';
    protected $name  = 'rebootActionForm';
    protected $title = 'rebootActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Reboot());
        $this->setConfirmMessage('conforimReboot');
        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
//        $this->addField((new Select('type'))
//            ->addHtmlAttribute('@change', "initReloadModal()"));
//        $this->addField(new Switcher('monitoring'));

    }

    public function reloadFormStructure()
    {
        $this->loadDataToForm();
    }


}
