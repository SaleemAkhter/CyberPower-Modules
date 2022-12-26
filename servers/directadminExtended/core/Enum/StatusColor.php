<?php

/* * ********************************************************************
 * DirectAdminExtended product developed. (Jan 23, 2019)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Enum;

/**
 * Description of StatusColor
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
final class StatusColor extends Enum
{
    const PENDING   = "f89406";
    const ACTIVE    = "46a546";
    const COMPLETED = "008b8b";
    const SUSPENDED = "0768b8";
    const CANCELLED = "bfbfbf";
    const FRAUD     = "000";
    
    public static function getColors(){
        return [
            "Pending"   => self::PENDING,
            "Active"    => self::ACTIVE,
            "Completed" => self::COMPLETED,
            "Suspended" => self::SUSPENDED,
            "Cancelled" => self::CANCELLED,
            "Fraud"     => self::FRAUD
        ];
    }
}
