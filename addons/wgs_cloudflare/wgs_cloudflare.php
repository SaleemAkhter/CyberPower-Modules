<?php

/* * ****************************************************************
 *  WGS Cloudflare Reseller WHMCS Addon Module By whmcsglobalservices.com
 *  Copyright whmcsglobalservices, All Rights Reserved
 * 
 *  Release: 01 May 2016
 *  WHMCS Version: v6,v7,v8
 *  Version: 5.0.7
 *  Update Date: 10 Oct, 2021
 * 
 *  By WHMCSGLOBALSERVICES    https://whmcsglobalservices.com
 *  Contact                   info@whmcsglobalservices.com
 *  
 *  This module is made under license issued by whmcsglobalservices.com
 *  and used under all terms and conditions of license.    Ownership of 
 *  module can not be changed.     Title and copy of    module  is  not
 *  available to any other person.
 * 
 *  @owner <whmcsglobalservices.com>
 *  @author <WHMCSGLOBALSERVICES>
 * ********************************************************** */

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

if (file_exists(__DIR__ . '/function.php'))
    include_once __DIR__ . '/function.php';

if (file_exists(__DIR__ . '/ajax/ajax.php'))
    include_once __DIR__ . '/ajax/ajax.php';

$clodflare = new Manage_Cloudflare();

function wgs_cloudflare_config()
{
    return [
        "name" => "WGS Cloudflare Reseller  Module ",
        "description" => "This module will allow you to manage CF configuration setting, Products setting, manage clientarea features.",
        "author" => "WHMCS GLOBAL SERVICES",
        "version" => "5.0.7",
        "language" => "english",
    ];
}

function wgs_cloudflare_activate()
{
    $clodflare = new Manage_Cloudflare();
    $clodflare->create_database();
    $clodflare->mergeExistingProducts();
}

function wgs_cloudflare_deactivate()
{
    $clodflare = new Manage_Cloudflare();
    $clodflare->drop_db();
}

function wgs_cloudflare_output($vars)
{
    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $_lang = $vars['_lang'];
    $clodflare = new Manage_Cloudflare();
    $clodflare->update_db();
    $clodflare->updateModuleSettings();
    $cssPath = '../modules/addons/wgs_cloudflare/assets/css/style.css';
    $jsPath = '../modules/addons/wgs_cloudflare/js/script.js';

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    Capsule::Schema()->table('mod_cloudflare__reseller_productsettings', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_productsettings', 'domains'))
            $table->integer('domains')->nullable();
    });
    
    Capsule::Schema()->table('mod_cloudflare__reseller_settings', function ($table) {
        if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_settings', 'pro_plan_price'))
            $table->string('pro_plan_price')->nullable();
        if (!Capsule::Schema()->hasColumn('mod_cloudflare__reseller_settings', 'biz_plan_price'))
            $table->string('biz_plan_price')->nullable();
    });

    $clodflare->mergeExistingProducts();

    if ($_GET['action'] == 'product') {
        include __DIR__ . '/includes/product_setting.php';
    } elseif ($_GET['action'] == 'features') {
        include __DIR__ . '/includes/edit_features.php';
    } elseif ($_GET['action'] == 'settings') {
        include __DIR__ . '/includes/settings.php';
    } elseif ($_GET['customaction'] == 'deleteproduct') {
        include __DIR__ . '/includes/del_product.php';
    } elseif ($_GET['action'] == 'zoneslist') {
        include __DIR__ . '/includes/zoneslist.php';
    } elseif ($_GET['action'] == 'zoneajax') {
        include __DIR__ . '/includes/zoneajax.php';
    } else {
        include __DIR__ . '/includes/dashboard.php';
    }
}
