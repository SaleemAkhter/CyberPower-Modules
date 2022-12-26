<?php

use Illuminate\Database\Capsule\Manager as Capsule;

function WGSCFCheckLicenseOnCron($vars)
{
   
        if (file_exists(dirname(__FILE__) . '/function.php'))
            require_once dirname(__FILE__) . '/function.php';

        $clodflare = new Manage_Cloudflare();

        $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
        $license = $clodflare->cloudflare_reseller_checkLicense($getSetting->license_key);

        $status = $license['status'];
        $table = 'mod_cloudflare__reseller_license';
        $rows = Capsule::table($table)->count();
        $query = Capsule::table($table)->first();
        $selectData = (array) $query;
        $value = ['status' => $status];
        if ($rows < 1) {
            Capsule::table($table)->insert($value);
        } else {
            Capsule::table($table)->where('id', $selectData['id'])->update($value);
        }
     
}

add_hook('DailyCronJob', 1, 'WGSCFCheckLicenseOnCron');
