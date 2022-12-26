<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated\DedicatedConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

/**
 * Class DedicatedLanguage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Languages extends DedicatedConfigSelect implements AdminArea
{
    public function getFieldValues()
    {

        $systemTemplates       = $this->config->getSystemTemplates();
        $systemTemplateValue   = $this->fieldsProvider->getField('dedicatedSystemTemplates');
        $requestSystemTemplate = $this->getRequestValue('packageconfigoption_dedicatedSystemTemplates');
        $requestSystemTemplate ? $systemTemplateValue = $requestSystemTemplate : null;

        $product = $this->config->getLanguages($systemTemplateValue && array_key_exists($systemTemplateValue, $systemTemplates) ? $systemTemplateValue : key($systemTemplates));

        return $product;
    }
}
