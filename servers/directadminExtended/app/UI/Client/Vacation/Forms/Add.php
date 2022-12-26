<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\InputGroupElements\DatePicker;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\HalfModalColumn;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\HalfModalSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Vacation\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\VacationCreate());

        $this->loadNameSection()
            ->loadMessageSection()
            ->loadTimeSection()
            ->loadDataToForm();
    }

    protected function loadNameSection()
    {

        $name = (new Sections\InputGroup('nameGroup'))
            ->addInputComponent((new InputGroupElements\Select('name'))->notEmpty())
            ->addInputAddon('dot', false, '@')
            ->addInputComponent((new InputGroupElements\Select('domains'))->addHtmlAttribute('bi-event-change', 'initReloadModal'))
            ->setDescription('');

        $this->addSection($name);

        return $this;
    }

    protected function loadMessageSection()
    {
        $message = (new FormGroupSection('messageSection'))
            ->addField(new Fields\Textarea('message'));

        $this->addSection($message);

        return $this;
    }

    protected function loadTimeSection()
    {
        $timeSection = new FormGroupSection('sectionsss');

        $leftColumn = new HalfModalSection('leftColumn');

        $startTime = (new Sections\InputGroup('startSection'))
            ->addInputComponent((new DatePicker('start'))->setDateFormat(DatePicker::FORMAT_DD_MM_YYYY_SLASH)->notEmpty())
            ->addInputComponent(new InputGroupElements\Select('starttime'))
            ->setDescription('');
        $leftColumn->addSection($startTime);
        $timeSection->addSection($leftColumn);

        $rightColumn = new HalfModalSection('rightColumn');

        $endTime = (new Sections\InputGroup('endSection'))
            ->addInputComponent((new DatePicker('end'))->setDateFormat(DatePicker::FORMAT_DD_MM_YYYY_SLASH)->notEmpty()->validDate())
            ->addInputComponent(new InputGroupElements\Select('endtime'))
            ->setDescription('');

        $rightColumn->addSection($endTime);
        $timeSection->addSection($rightColumn);



        $this->addSection($timeSection);
        return $this;
    }

}
