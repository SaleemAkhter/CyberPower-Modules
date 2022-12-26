<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;

class Settings extends BaseForm implements ClientArea
{
    protected $id    = 'settingsForm';
    protected $name  = 'settingsForm';
    protected $title = 'settingsForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\SpamFilterSettings());

        $this->loadFields()
            ->loadDataToForm();
    }

    protected function loadFields()
    {
        $domain     = (new Fields\Select('domains'))->addHtmlAttribute('bi-event-change', 'initReloadModal');
        $adult      = new Fields\Select('adult');
        $options    = new Fields\Select('filterOptions');

        $this->addField($domain)
            ->addField($adult)
            ->addField($options);

        return $this;
    }

    protected function reloadFormStructure()
    {
            $this->dataProvider->reload();
            $this->loadDataToForm();
    }

}
