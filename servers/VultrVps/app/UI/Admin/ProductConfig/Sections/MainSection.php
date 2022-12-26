<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Sections;

use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Fields\OsSelect;
use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Fields\SnapshotSelect;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\SectionLuRow;


class MainSection extends BoxSection implements AdminArea
{
    protected $id = 'region';
    protected $name = 'region';
    protected $title = 'regionTitle';

    public function initContent()
    {
        //init rows
        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $columRight = new HalfPageSection('hps');
        $columRight->setMainContainer($this->mainContainer);
        $row->addSection($columRight);
        $this->addSection($columRight);
        //region
        $field = new Select('region');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //plan
        $field = new Select('plan');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $columRight->addField($field);
        //os_id
        $field = new Select('os_id');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //iso_id
        $field = new Select('iso_id');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //snapshot_id
        $field = new Select('snapshot_id');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //user_data
        $field = new Textarea('user_data');
        $field->setDescription('description');
        $field->addGroupName('mgpci');
        $columRight->addField($field);
        /*applications
        $field = new Select('app_id');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        */
        //enable_ipv6
        $field = new Switcher('enable_ipv6');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //backups
        $field = new Switcher('backups');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $columRight->addField($field);


    }
}
