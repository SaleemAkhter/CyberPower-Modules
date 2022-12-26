<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * BaseEditModal controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseEditModal extends BaseContainer
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Forms;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Modal;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\ModalActionButtons;

    protected $id    = 'baseEditModal';
    protected $name  = 'baseEditModal';
    protected $title = 'baseEditModal';
    
    public function __construct($baseId = null)
    {
        parent::__construct($baseId);
        
        $this->setModalSizeMedium();
    }
}
