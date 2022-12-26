<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Forms\ChangeHostnameForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ChangeHostnameModal extends BaseEditModal implements AdminArea
{

    protected $id    = 'changeHostnameModal';
    protected $name  = 'changeHostnameModal';
    protected $title = 'changeHostnameModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new ChangeHostnameForm());
    }

}
