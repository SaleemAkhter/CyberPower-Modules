<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Decorators;

use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Class OvhServerType
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class OvhServerType
{
    /**
     * Decorate Ovh Server Type column for datatable
     *
     * @param null $serverType
     * @return string
     */
    public static function decorate($serverType = null)
    {
        if(!$serverType)
        {
            return '-';
        }
        $lang = ServiceLocator::call('lang');

        return $lang->absoluteTranslate('ovh', 'server','type', $serverType);
    }

    /**
     *
     * Replace Html column content
     *
     * @return string
     */
    public static function columnHtmldecorate()
    {
//        return '<span :style="\'color:\' + (ovhServerTypeFormatter(dataRow.ovhServerType))"><b>{{dataRow.ovhServerType}}</b></span>';
        return '<span><b>{{dataRow.ovhServerType}}</b></span>';
    }
}
