<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Vps\VpsConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
/**
 * Class Localization
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Localizations extends VpsConfigSelect implements AdminArea
{
    public function getFieldValues()
    {
        $requestProduct = $this->getRequestValue('packageconfigoption_vpsProduct');
        $plan = $requestProduct ? $requestProduct:  $this->fieldsProvider->getField('vpsProduct');
        return $this->config->getLocalizations( $plan);
    }

}