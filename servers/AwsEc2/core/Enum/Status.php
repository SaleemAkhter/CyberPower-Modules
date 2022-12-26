<?php

/* * ********************************************************************
 * AwsEc2 product developed. (Jan 8, 2019)
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

namespace ModulesGarden\Servers\AwsEc2\Core\Enum;

/**
 * Description of Status
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
final class Status extends Enum
{

    const DEBUG    = "debug";
    const ERROR    = "error";
    const INFO     = "info";
    const SUCCESS  = "success";
    const CRITICAL = "critical";
    
}
