<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api;

/**
 * Class Contats
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Contacts
{
    CONST ADMIN = 'contactAdmin';
    CONST BILLING = 'contactBilling';
    CONST TECH = 'contactTech';

    public static function makeContactsArray($contactAdmin = '', $contactBilling = '', $contactTech = '')
    {
        return [
            SELF::ADMIN   => $contactAdmin,
            SELF::BILLING => $contactBilling,
            SELF::TECH    => $contactTech,
        ];
    }
}