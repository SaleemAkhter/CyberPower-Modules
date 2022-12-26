<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Lang\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Class AutomationSettings
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AutomationSettings
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;

    const DO_NOTHING = 'doNothing';
    const STOP       = 'stop';

    public static function getOptionsOnSuspendAction()
    {
        return [
            self::DO_NOTHING => self::DO_NOTHING,
            self::STOP       => self::STOP
        ];
    }

    public static function getLangedOptionsOnSuspendAction()
    {
        $options = self::getOptionsOnSuspendAction();

        $lang = ServiceLocator::call('lang');

        foreach ($options as $key => &$value)
        {
            $value = $lang->translate('suspendAction', $value);
        }

        return $options;
    }
}