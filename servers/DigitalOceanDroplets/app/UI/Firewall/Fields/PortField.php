<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Fields;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Validators\PortValidator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-08-27
 * Time: 13:30
 */

class PortField extends Text
{
    protected $id   = 'portField';
    protected $name = 'portField';

    public function setPortValidator(){
        $this->addValidator(new PortValidator());
    }

}