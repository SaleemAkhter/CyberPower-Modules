<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks\Enums;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Class Edit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Edit extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'diskEditForm';
    protected $name  = 'diskEditForm';
    protected $title = 'diskEditForm';

    public function initContent()
    {
        $this->setProvider(new Disk());
        $this->setFormType(FormConstants::UPDATE);
        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField(new Hidden('id'));
        $this->addField(new Switcher('monitoring'));
        $this->addField((new Text('bandwidthLimit'))->notEmpty()->isIntNumberBetween());
        $this->addField((new Text('lowFreeSpaceThreshold'))->notEmpty()->isIntNumberBetween());
        $this->addField((new Text('size'))->isIntNumberBetween());
        $this->addField((new Select('type'))->notEmpty()->setAvailableValues(Enums::getTypes()));
        $this->addField((new Select('state'))->notEmpty()->setAvailableValues(Enums::getStatesFormatted()));
    }

}