<?php

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Register a hook with WHMCS.
 *
 * This sample demonstrates triggering a service call when a change is made to
 * a client profile within WHMCS.
 *
 * For more information, please refer to https://developers.whmcs.com/hooks/
 *
 * add_hook(string $hookPointName, int $priority, string|array|Closure $function)
 */
use WHMCS\View\Menu\Item as MenuItem;

add_hook('ClientAreaPrimarySidebar', 1, function($sidebar) {
    try {

        if (!is_null($sidebar->getChild('Already Registered'))) {
                $sidebar->removeChild('Already Registered');
        }      
    } catch (Exception $e) {
        logModuleCall('devtest', 'Client Area Primary Sidebar', '', $e->getMessage(), '', '');
    }
});
