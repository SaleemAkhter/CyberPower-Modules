<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated\DedicatedConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;

/**
 * Class DedicatedSystemTemplates
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @property  Api $api
 */
class DurationSelect extends DedicatedConfigSelect implements AdminArea
{


    public function initContent()
    {
        $this->initIds("packageconfigoption_duration");
    }


    public function prepareAjaxData()
    {
        if($this->config == null)
        {
            return;
        }
        $lang = ServiceLocator::call('lang');
        foreach (['P1M','P3M','P6M',"P1Y","P2Y"] as $value)
        {
            $toLang      = "$value months";
            $this->availableValues[] = [
                'key'   => $value,
                'value' =>  $lang->absoluteT($value)
            ];
        }
        //set selected value
        $this->setValue( $this->fieldsProvider->getField('duration'));
    }

}
