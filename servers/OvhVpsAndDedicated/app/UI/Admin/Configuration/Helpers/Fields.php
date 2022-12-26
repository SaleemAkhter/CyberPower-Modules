<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers;

/**
 * Class Fields
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Fields
{
    const SELECT   = 'select';
    const SWITCHER = 'switcher';

    public static function getOptionsFields()
    {
        return [

            'Plesk'           => [
                'action'    => 'hide',
                'fieldType' => self::SELECT,
            ],
            //            'additionalDisk'  => self::SELECT,
            'Cpanel'          => [
                'action'    => 'hide',
                'fieldType' => self::SWITCHER
            ],
//            'AutomatedBackup' => [
//                'action'    => 'hide',
//                'fieldType' => self::SWITCHER
//            ],
//            'Ftpbackup'       => [
//                'action'    => 'hide',
//                'fieldType' => self::SWITCHER
//            ],
            'Snapshot'        => [
                'action'    => 'show',
                'fieldType' => self::SWITCHER
            ],
            'Windows'         => [
                'action'    => 'hide',
                'fieldType' => self::SWITCHER,
            ],
        ];
    }
}