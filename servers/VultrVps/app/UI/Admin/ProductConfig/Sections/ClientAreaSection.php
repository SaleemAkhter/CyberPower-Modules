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
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;


class ClientAreaSection extends BoxSection implements AdminArea
{
    protected $id = 'clientAreaSection';
    protected $name = 'clientAreaSection';
    protected $title = 'clientAreaSectionTitle';

    protected $permissions=['backup','console','firewall','bandwidth','changeOs','snapshot'];

    public function initContent()
    {
        $this->leftSection  = new HalfPageSection('leftSection');
        $this->rightSection = new HalfPageSection('rightSection');
        $this->addSection($this->leftSection)
            ->addSection($this->rightSection);
        //add permissions
        foreach ($this->permissions as $controller){
            $field = new Switcher('permission'.ucfirst($controller));
            $field->addGroupName('mgpci');
            $field->setDescription('description');
            $this->addField($field);
        }
        //changeOsId
        $field = new Select('changeOsId[]');
        $field->enableMultiple();
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
    }

    public function addField($field){
        $total = count($this->leftSection->getFields()) + count($this->rightSection->getFields());
        if($total % 2 == 0){
            $this->leftSection->addField($field);
        }else{
            $this->rightSection->addField($field);
        }
        return $this;
    }
}
