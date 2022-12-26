<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Interfaces;

/**
 * Class AccountAction
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
interface AccountAction
{
    public function create();

    public function suspend();

    public function unsuspend();

    public function terminate();

    public function changePackage();

    public function assignDomainAndIpToService();

}