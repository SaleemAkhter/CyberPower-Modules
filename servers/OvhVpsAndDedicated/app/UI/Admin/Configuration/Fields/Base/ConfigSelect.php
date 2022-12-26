<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts\ConfigBase;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

/**
 * Class ConfigSelect
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigSelect extends Select implements AdminArea
{
    /**
     * Class Name
     *
     * @var bool|string
     */
    protected $className;

    /**
     * @var FieldsProvider
     */
    protected $fieldsProvider;

    /**
     * @var string
     */
    protected $optionPrefix = '';

    /**
     * @var ConfigBase
     */
    protected $config = null;

    public function __construct($baseId = null)
    {
        $this->className      = substr(strrchr(get_called_class(), "\\"), 1);

        parent::__construct("packageconfigoption_". $this->optionPrefix . $this->className);

        $this->fieldsProvider = new FieldsProvider($this->getRequestValue('id'));
    }

    public function prepareAjaxData()
    {
        if($this->config == null)
        {
            return;
        }

        $values = $this->getFieldValues();
        $selected = $this->getSelectedValue();
        $valueExist = false;

        $availableValues = [];
        foreach ($values as $key => $value)
        {
            $availableValues[] = [
                'key'   => $key,
                'value' => $value,
            ];
            if($key == $selected)
            {
                $valueExist = true;
            }
        }

        $this->setAvailableValues($availableValues);
        $selectedValue = $valueExist ? $selected : $availableValues[0]['key'];
        $this->setSelectedValue((string) $selectedValue);

        $this->loadOptions();
    }

    protected function getFieldValues()
    {
        $method = "get" . $this->className;

        if(!method_exists($this->config, $method))
        {
            $class = get_class($this->config);
            Helper\errorLog("{$method} does not exist in class {$class}");
            return;
        }

        return $this->config->{$method}();
    }

    protected function getSelectedValue()
    {
        return $this->fieldsProvider->getField($this->optionPrefix . $this->className);
    }

    protected function loadOptions()
    {

    }
}