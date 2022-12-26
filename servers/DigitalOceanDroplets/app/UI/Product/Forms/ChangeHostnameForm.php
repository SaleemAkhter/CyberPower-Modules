<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Forms;


use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Providers\ChangeHostnameProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ChangeHostnameForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'changeHostnameForm';
    protected $name  = 'changeHostnameForm';
    protected $title = 'changeHostnameForm';

    public function initContent()
    {

        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new ChangeHostnameProvider());
        $this->addField(new Hidden('id'));
        $this->addField((new Text('hostname')));
        $this->loadDataToForm();
    }

}
