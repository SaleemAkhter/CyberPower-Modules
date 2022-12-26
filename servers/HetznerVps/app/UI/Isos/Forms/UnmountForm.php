<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Providers\UnmountProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class UnmountForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'unmountForm';
    protected $name  = 'unmountForm';
    protected $title = 'unmountForm';

    public function initContent()
    {
        $this->setFormType('unmount');
        $this->setProvider(new UnmountProvider());
        $this->setConfirmMessage('unmountConfirm',  ['description' => null]);
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('description'));
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['unmount'];
    }


}
