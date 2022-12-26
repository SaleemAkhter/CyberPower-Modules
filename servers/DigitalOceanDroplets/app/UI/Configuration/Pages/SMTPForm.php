<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms\SMTPFields;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SMTPForm extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'sMTPConfigurationForm';
    protected $name  = 'sMTPConfigurationForm';
    protected $title = 'sMTPConfigurationForm';

    public function initContent()
    {
        $this->setTitle(Lang::getInstance()->T('sMTPConfigurationForm'));
        $this->addElement(new SMTPFields());
        $this->addButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Buttons\TestSMTPConnection());
    }

}
