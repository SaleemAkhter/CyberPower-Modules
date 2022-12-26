<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Pages;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdown;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneFormExtSections;
use ModulesGarden\WordpressManager\App\UI\LoggerManager\Providers\OtherSettingsProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;


class OtherSettingsPage extends BaseStandaloneFormExtSections implements AdminArea
{
    protected $id    = 'otherSettingsPage';
    protected $name  = 'otherSettingsPage';
    protected $title = 'otherSettingsPageTitle';

    public function initContent()
    {
        $this->setProvider((new OtherSettingsProvider()));
        $this->setFormType('update');
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        $section = new HalfPageSection();


        $section->addField((new Text('googleApiToken'))->setDescription('description'));
        $section->addField((new Select('cron'))
            ->setId('cron')
            ->setDefaultValue(1)
            ->setName('cron')
            ->setTitle('cron')
            ->setDescription('description'));
        $section->addField((new Select('protocols'))
            ->setDescription('description')
            ->setDefaultValue([1, 2, 3, 4])
            ->enableMultiple()
            ->notEmpty());
        $section->addField((new Switcher('extendedView'))
                ->setDescription('description')
        );

        $section2 = new BoxSection();
        $section2->addSection($section);

        $this->addSection($section2);
    }
}
