<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Console;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;

/**
 * Class ConsoleOpen
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConsoleOpen extends BaseForm implements AdminArea
{
    const OPEN = 'open';

    protected $id    = 'consoleOpenForm';
    protected $name  = 'consoleOpenForm';
    protected $title = 'consoleOpenForm';

    protected $allowedActions = [
        self::OPEN
    ];

    public function initContent()
    {
        $this->setFormType(self::OPEN);
        $this->setProvider(new Console());
        $this->setConfirmMessage('consoleOpenForm');
        $this->loadDataToForm();
    }

}