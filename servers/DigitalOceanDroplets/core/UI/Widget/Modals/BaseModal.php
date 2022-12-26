<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModal extends BaseContainer
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Forms;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Modal;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\ModalActionButtons;

    protected $id    = 'baseModal';
    protected $name  = 'baseModal';
    protected $title = 'baseModal';
}
