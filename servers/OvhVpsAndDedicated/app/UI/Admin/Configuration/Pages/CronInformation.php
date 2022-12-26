<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Others\CronDescription;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseConfigurationPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CronInformation extends BaseConfigurationPage implements ClientArea, AdminArea
{

    protected $id    = 'cronInformation';
    protected $name  = 'cronInformationName';
    protected $title = 'cronInformationTitle';

    public function initContent()
    {
        $this->addElement(CronDescription::class);
    }

}
