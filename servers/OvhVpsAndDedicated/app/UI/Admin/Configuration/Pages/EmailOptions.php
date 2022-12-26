<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\EmailTemplate;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseOptionsPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EmailOptions extends BaseOptionsPage implements ClientArea, AdminArea
{
    protected $id    = 'emailOptionsForm';
    protected $name  = 'emailOptionsFormName';
    protected $title = 'emailOptionsFormTitle';

    public function initContent()
    {
        $this->addElement(new EmailTemplate());
    }

}
