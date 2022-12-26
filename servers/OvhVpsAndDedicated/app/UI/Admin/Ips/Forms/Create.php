<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms;



use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\Ip;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends Update implements ClientArea, AdminArea
{

    protected $id    = 'createForm';
    protected $name  = 'createForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Ip());
        $this->initFields();
    }
}