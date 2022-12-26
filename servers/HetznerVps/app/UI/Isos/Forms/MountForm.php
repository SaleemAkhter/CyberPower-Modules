<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Forms;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class MountForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'mountForm';
    protected $name  = 'mountForm';
    protected $title = 'mountForm';

    public function initContent()
    {
        $this->setFormType('mount');
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Isos\Providers\MountProvider());
        $this->setConfirmMessage('mountConfirm', ['description' => null]);
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('description'));
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['mount'];
    }


}
