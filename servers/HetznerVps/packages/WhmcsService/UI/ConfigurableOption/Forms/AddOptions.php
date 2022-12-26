<?php

namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Forms;

use ModulesGarden\Servers\HetznerVps\Core\Traits\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\Enum;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Providers\Options;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Traits\ConfigurableOptionsConfig;

class AddOptions extends BaseForm implements AdminArea
{
    use ConfigurableOptionsConfig;
    use Lang;

    protected $id = 'addOptionsForm';
    protected $name = 'addOptionsForm';
    protected $title = 'addOptionsFormTitle';

    public function initContent()
    {
        $provider = new Options();
        $this->setProvider($provider);

        $this->setFormType(FormConstants::CREATE);

        $this->loadLang();
        $this->lang->addReplacementConstant('configurableOptionsNameUrl', '<a style="    color: #31708f; text-decoration: underline;" href="https://docs.whmcs.com/Addons_and_Configurable_Options" target="blank">here</a>');

        $this->addInternalAlert('configurableOptionsNameInfo', AlertTypesConstants::INFO, AlertTypesConstants::SMALL);

        $this->loadConfigurableOptionsList();
        foreach ($this->configOptionsList as $configOption)
        {
            $rawName = $this->trimConfigOptionName($configOption[Enum::OPTION_NAME]);
            $field = new Switcher($rawName);
            $field->setRawTitle($configOption[Enum::OPTION_NAME]);
            $field->addGroupName('configOptions');
            $field->setDefaultValue('on');
            $this->addField($field);
        }

        $this->loadDataToForm();
    }
}
