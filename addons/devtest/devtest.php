<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
function copy_folder($src, $dst) { 
  
    // open the source directory
    $dir = opendir($src); 
  
    // Make the destination directory if not exist
    @mkdir($dst); 
  
    // Loop through the files in source directory
    while( $file = readdir($dir) ) { 
  
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) 
            { 
  
                // Recursively calling custom copy function
                // for sub directory 
                copy_folder($src . '/' . $file, $dst . '/' . $file); 
  
            } 
            else { 
                copy($src . '/' . $file, $dst . '/' . $file); 
            } 
        } 
    } 
  
    closedir($dir);
} 

function removeDirectory($path) {

	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);

	return;
}
/**
 * Define addon module configuration parameters.
 *
 * Includes a number of required system fields including name, description,
 * author, language and version.
 *
 * Also allows you to define any configuration parameters that should be
 * presented to the user when activating and configuring the module. These
 * values are then made available in all module function calls.
 *
 * Examples of each and their possible configuration parameters are provided in
 * the fields parameter below.
 *
 * @return array
 */
function devtest_config()
{
    return [
        // Display name for your module
        'name' => 'Customization',
        // Description displayed within the admin interface
        'description' => '',
        // Module author name
        'author' => 'Arslan ud Din Shafiq',
        // Default language
        'language' => 'english',
        // Version number
        'version' => '1.0',
    ];
}

/**
 * Activate.
 *
 * Called upon activation of the module for the first time.
 * Use this function to perform any database and schema modifications
 * required by your module.
 *
 * This function is optional.
 *
 * @see https://developers.whmcs.com/advanced/db-interaction/
 *
 * @return array Optional success/failure message
 */
function devtest_activate()
{
    // Create custom tables and schema required by your module
    try {

        global $CONFIG;

        if (isset($_SESSION['Template'])){
            $template = $_SESSION['Template'];
        } else {
            $template = $CONFIG['Template'];
        }
        if (!file_exists(__DIR__. '/old/login.tpl'))
        {
            copy(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/login.tpl', __DIR__. '/old/login.tpl');
            copy(__DIR__. '/templates/login.tpl', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/login.tpl');
        }
        if (!file_exists(__DIR__. '/old/clientregister.tpl'))
        {
            copy(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/clientregister.tpl', __DIR__. '/old/clientregister.tpl');
            copy(__DIR__. '/templates/clientregister.tpl', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/clientregister.tpl');
        }
        if (!file_exists(__DIR__. '/old/head.tpl'))
        {
            copy(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/includes/head.tpl', __DIR__. '/old/head.tpl');
        }

        $oldHeadPath = str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/includes/head.tpl';
        $newHeadPath = __DIR__. '/templates/head.tpl';

        $oldHead = file_get_contents($oldHeadPath);
        $newHead = file_get_contents($newHeadPath);
        $headFile = '{if ($smarty.server.REQUEST_URI|strstr:"login") || ($smarty.server.REQUEST_URI|strstr:"register.php")}';
        $headFile .= $newHead;
        $headFile .= '{else}';
        $headFile .= $oldHead;
        $headFile .= '{/if}';
        file_put_contents($oldHeadPath, $headFile);

        if (!file_exists(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/assets/antler'))
        {
            mkdir(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/assets/antler');
        }
        copy_folder( __DIR__. '/templates/assets/', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/assets/antler');
        return [
            // Supported values here include: success, error or info
            'status' => 'success',
            'description' => 'Activated successfully.',
        ];
    } catch (\Exception $e) {
        return [
            // Supported values here include: success, error or info
            'status' => "error",
            'description' => "{$e->getMessage()}",
        ];
    }
}

/**
 * Deactivate.
 *
 * Called upon deactivation of the module.
 * Use this function to undo any database and schema modifications
 * performed by your module.
 *
 * This function is optional.
 *
 * @see https://developers.whmcs.com/advanced/db-interaction/
 *
 * @return array Optional success/failure message
 */
function devtest_deactivate()
{
    // Undo any database and schema modifications made by your module here
    try {
        global $CONFIG;

        if (isset($_SESSION['Template'])){
            $template = $_SESSION['Template'];
        } else {
            $template = $CONFIG['Template'];
        }

        if (file_exists(__DIR__. '/old/login.tpl'))
        {
            copy(__DIR__. '/old/login.tpl', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/login.tpl');
            unlink(__DIR__. '/old/login.tpl');
        }
        if (file_exists(__DIR__. '/old/clientregister.tpl'))
        {
            copy( __DIR__. '/old/clientregister.tpl', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/clientregister.tpl');
            unlink(__DIR__. '/old/clientregister.tpl');
        }
        if (file_exists(__DIR__. '/old/head.tpl'))
        {
            copy( __DIR__. '/old/head.tpl', str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/includes/head.tpl');
            unlink(__DIR__. '/old/head.tpl');
        }
        removeDirectory(str_replace('modules/addons', '' ,dirname(__DIR__)) . 'templates/' . $template. '/assets/antler');
        return [
            // Supported values here include: success, error or info
            'status' => 'success',
            'description' => 'Module deactivated successfully. ',
        ];
    } catch (\Exception $e) {
        return [
            // Supported values here include: success, error or info
            "status" => "error",
            "description" => "{$e->getMessage()}",
        ];
    }
}

/**
 * Upgrade.
 *
 * Called the first time the module is accessed following an update.
 * Use this function to perform any required database and schema modifications.
 *
 * This function is optional.
 *
 * @see https://laravel.com/docs/5.2/migrations
 *
 * @return void
 */
function devtest_upgrade($vars)
{
    $currentlyInstalledVersion = $vars['version'];
}

