<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\SMTPFields;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseConfigurationPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseOptionsPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SMTPForm extends BaseOptionsPage implements ClientArea, AdminArea
{
    protected $id    = 'sMTPConfigurationForm';
    protected $name  = 'sMTPConfigurationForm';
    protected $title = 'sMTPConfigurationFormTitle';

    public function initContent()
    {
        $this->addElement(new SMTPFields());
        $this->addButton(new \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons\TestSMTPConnection());
    }
}
