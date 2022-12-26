<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 20, 2017)
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

/**
 * Description of ModuleLog
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Loger
{
    private $module;
    private $replaceVars = [];

    function __construct($module, $replaceVars = [])
    {
        $this->module      = $module;
        $this->replaceVars = $replaceVars;
    }

    /**
     * Log On Module Logs
     * @param string $action
     * @param string $requestString
     * @param string $responseData
     * @param array $processedData
     */
    public function log($action, $requestString, $responseData, $processedData)
    {
        logModuleCall($this->module, $action, $requestString, $responseData, $processedData, $this->replaceVars);
    }
}
