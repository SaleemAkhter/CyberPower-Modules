<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 06.10.2018
 * Time: 10:41
 */

namespace ModulesGarden\DirectAdminExtended\App\Services\Migration\Traits;


trait SwitcherFields
{

    protected $newActivatedValue    = 'on';
    protected $newExcludedValue     = 'off';
    protected $oldActivatedValue    = 1;

    protected $switcherFields = [
        'cgi',
        'php',
        'ssh',
        'ssl',
        'aftp',
        'cron',
        'spam',
        'sysinfo',
        'catchall',
        'dnscontrol',
        'dedicated_ip',
        'suspend_at_limit',
    ];


    protected function isSwitcher($option)
    {

        return in_array($option, $this->switcherFields);
    }

    protected function getSwitcherValue($value)
    {

        return ($value == $this->oldActivatedValue) ? $this->newActivatedValue : $this->newExcludedValue;
    }
}