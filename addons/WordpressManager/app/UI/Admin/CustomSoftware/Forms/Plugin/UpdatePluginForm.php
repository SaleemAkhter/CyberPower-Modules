<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Plugin;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Providers\Plugin\CustomPluginProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;

class UpdatePluginForm extends BaseForm implements AdminArea
{
    public function getAllowedActions()
    {
        return ['update'];
    }

    public function initContent()
    {
        $this->initIds('updatePluginForm');
        $this->addClass('lu-row');
        $this->setFormType('update');
        $this->setProvider(new CustomPluginProvider());
        $this->loadRequestObj();
        $this->initFields();
    }

    public function initFields()
    {
        $section = new RawSection('section');
        $section->setMainContainer($this->mainContainer);
        $section->addField(new Fields\Hidden('id'));
        $section->addField((new Fields\Switcher('enable')));
        $section->addField((new Fields\Text("name"))->notEmpty());
        $section->addField((new Fields\Textarea("description")));
        $section->addField((new Fields\Text("url"))->notEmpty()->setPlaceholder('https://example.com/customplugin.zip'));
        $section->addField((new Fields\Text("version"))->notEmpty()->setPlaceholder('1.0.0'));
        $this->addSection($section);
        $this->loadDataToForm();
    }
}
