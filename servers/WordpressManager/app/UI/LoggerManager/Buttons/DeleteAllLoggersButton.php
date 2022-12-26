<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Buttons;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonModal;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals\DeleteAllLoggersModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

/**
 * Description of AssignTldButton
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteAllLoggersButton extends ButtonModal implements AdminArea
{
    protected $id    = 'deleteAllLoggersButton';
    protected $name  = 'deleteAllLoggersButton';
    protected $title = 'deleteAllLoggersButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\')';

        $this->setModal(new DeleteAllLoggersModal());
    }
}
