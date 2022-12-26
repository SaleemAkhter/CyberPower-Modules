<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated\DedicatedConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

/**
 * Class DedicatedSystemTemplates
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @property  Api $api
 */
class PricingModeSelect extends DedicatedConfigSelect implements AdminArea
{


    public function initContent()
    {
        $this->initIds("packageconfigoption_pricingMode");
    }


    public function prepareAjaxData()
    {
        $this->availableValues= [
            [
                'key'   => 'default',
                'value' =>  sl('lang')->abtr('pricingMode', 'default')
            ],
            [
                'key'   => 'degressivity12',
                'value' =>  sl('lang')->abtr('pricingMode', 'degressivity12')
            ],
            [
                'key'   => 'degressivity24',
                'value' =>  sl('lang')->abtr('pricingMode', 'degressivity24')
            ]

        ];
        $this->setValue( $this->fieldsProvider->getField('pricingMode'));
    }

}
