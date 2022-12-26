<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Helpers\Formatter;

/**
 * Class StateFormatter
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class State
{
    const STATE_LABEL = '<span class="lu-label lu-label--%s lu-label--status">%s</span>';

    const RUNNING = 'Running';
    const STOPPED = 'Stopped';
    const STOPPING = 'Stopping';
    const INSTALLING = 'Installing';
    const MAINTENANCE = 'Maintenance';
    const REBOOTING = 'Rebooting';
    const UPGRADING = 'Upgrading';
    const HACKED = 'Hacked';
    const HACKED_BLOCKED = 'HackedBlocked';
    const OK = 'Ok';
    const ERROR = 'Error';

    const UP = 'Up';
    const DOWN = 'Down';

    const TRUE = 'Enabled';
    const FALSE = 'Disabled';

    const CONNECTED = "Connected";
    const DISCONNECTED = "Disconnected";
    const PENDING = 'Pending';


    const PRIMARY = 'primary';
    const SUCCESS = 'success';
    const DANGER = 'danger';
    const WARNING = 'warning';
    const INFO = 'info';

    const STATUS = 'status';

    /**
     * @param $type
     * @param $value
     * @return string
     */
    public static function format($type, $value)
    {
        return sprintf(self::STATE_LABEL, $type, $value);
    }

    /**
     * @param $value
     * @return string
     */
    public static function vps($value)
    {
        $value = self::convert($value);
        return self::format(self::getType($value), $value);
    }

    /**
     * @param $value
     * @return string
     */
    public static function getType($value)
    {
        switch ($value)
        {
            case self::MAINTENANCE:
                return self::PRIMARY;

            case self::RUNNING:
            case self::OK:
            case self::UP:
            case self::CONNECTED:
            case self::TRUE:
                return self::SUCCESS;

            case self::DISCONNECTED:
            case self::STOPPED:
            case self::STOPPING:
            case self::ERROR:
            case self::DOWN:
            case self::FALSE:
                return self::DANGER;

            case self::HACKED:
            case self::HACKED_BLOCKED:
                return self::WARNING;

            case self::PENDING:
            case self::REBOOTING:
            case self::UPGRADING:
                return self::INFO;
        }
        return self::STATUS;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function convertTrueAndFalseToEnabledAndDisabledWithLabel($data)
    {
        foreach ($data as &$value)
        {
            if(is_bool($value))
            {
                $value = $value ? 'Enabled' : 'Disabled';
                $value = self::format(self::getType($value), $value);
            }
        }

        return $data;
    }

    /**
     * @param $value
     * @return string
     */
    public static function convert($value)
    {
        switch ($value)
        {
            case self::OK:
                return self::RUNNING;
        }

        return $value;
    }
}
