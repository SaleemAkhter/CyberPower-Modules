<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 6, 2017)
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

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager as main;

/**
 * Description of Whmcs
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class WhmcsHelper
{

    public static function getParams($hostingId)
    {
        if (!function_exists('ModuleBuildParams'))
        {
            require_once main\Core\ModuleConstants::getFullPathWhmcs('includes') . DIRECTORY_SEPARATOR . "modulefunctions.php";
        }
        return \ModuleBuildParams($hostingId);
    }

    /**
     * Returns todays date
     *
     * By default returns the format defined in General Settings > Localisation > Date Format
     *
     * @param bool $applyClientDateFormat Set true to apply Localisation > Client Date Format
     *
     * @return string
     */
    public static function getTodaysDate($applyClientDateFormat)
    {
        return getTodaysDate($applyClientDateFormat);
    }

    /**
     * Formats a MySQL Date/Timestamp value to system settings
     *
     * @param string $datetimestamp The MySQL Date/Timestamp value
     * @param bool $includeTime Pass true to include the time in the result
     * @param bool $applyClientDateFormat Set true to apply Localisation > Client Date Format
     *
     * @return string
     */
    public static function fromMySQLDate($date, $includeTime, $applyClientDateFormat=false)
    {
        return fromMySQLDate($date, $includeTime, $applyClientDateFormat);
    }

    /**
     * Converts a date entered in the system setting format to a MySQL Date/Timestamp
     *
     * @param string $userInputDate
     *
     * @return string Format: 2016-12-30 23:59:59
     */
    public static function toMySQLDate($date)
    {
        return toMySQLDate($date);
    }

    /**
     * Log activity.
     *
     * @param string $message The message to log
     * @param int $userId An optional user id to which the log entry relates
     */
    public static function logActivity($message, $userId = 0)
    {
        logActivity('Message goes here', 0);
    }

    /**
     * Log module call.
     *
     * @param string $module The name of the module
     * @param string $action The name of the action being performed
     * @param string|array $requestString The input parameters for the API call
     * @param string|array $responseData The response data from the API call
     * @param string|array $processedData The resulting data after any post processing (eg. json decode, xml decode, etc...)
     * @param array $replaceVars An array of strings for replacement
     * @see logModuleCall('provisioningmodule',__FUNCTION__,$params,$e->getMessage(),  $e->getTraceAsString()
     */
    public static function logModuleCall($module, $action, $requestString, $responseData, $processedData, $replaceVars)
    {

        logModuleCall($module, $action, $requestString, $responseData, $processedData, $replaceVars);
    }

    /**
     * Get clients currency
     *
     * Required before making a call to formatCurrency
     *
     * @param int $userId
     *
     * @return array
     */
    public static function getCurrency($userId)
    {
        return getCurrency($userId);
    }

    /**
     * Format currency
     *
     * @param float $amount
     * @param int   $currencyId
     *
     * @return \WHMCS\View\Formatter\Price
     */
    public static function formatCurrency($amount, $currencyId)
    {
        return formatCurrency($amount, $currencyId);
    }
}
