<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Options;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Vps\VpsConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Prepare\Prepare;

/**
 * Class Cpanel
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Cpanel extends VpsConfigSelect implements AdminArea
{
    public function getFieldValues()
    {
        $productValue   = $this->fieldsProvider->getField('vpsProduct');
        $requestProduct = $this->getRequestValue('packageconfigoption_vpsProduct');
        $requestProduct ? $productValue = $requestProduct : null;

        if($this->getRequestValue('packageconfigoption_vpsDistribution') != 'cpanel')
        {
            return [];
        }

        $fieldsToAdd = $this->config->getOptions($productValue);
        $options = Prepare::vpsOptionsValues($fieldsToAdd['cpanel']);

        return $options;
    }
}