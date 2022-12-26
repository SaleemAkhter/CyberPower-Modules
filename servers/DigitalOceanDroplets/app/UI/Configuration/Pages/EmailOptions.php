<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms\EmailTemplate;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class EmailOptions extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'emailOptionsForm';
    protected $name  = 'emailOptionsForm';
    protected $title = 'emailOptionsForm';

    public function initContent()
    {
        $this->setTitle(Lang::getInstance()->T('emailOptionsForm'));
        $this->addElement(new EmailTemplate());
    }

}
