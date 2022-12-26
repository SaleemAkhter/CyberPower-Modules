<?php

use WHMCS\Database\Capsule;

global $whmcs;
$clodflare = new Manage_Cloudflare();

$getdata = $clodflare->get_settings();

$license_key = $getdata->license_key;

$getlicense = $clodflare->cloudflare_reseller_checkLicense($license_key, $localkey);

$status = $getlicense['status'];
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
$class = ($getlicense['status'] == 'Active') ? 'valid' : 'invalid';
?>

<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/style.css">
<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/fonts.css">
<ul class="nav nav-pills">
    <li class="active"><a href="<?php echo $modulelink; ?>"><i class="wgs-flat-icon flaticon-line-graph" aria-hidden="true"></i><?php echo $_lang['Dashboard']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=settings"><i class="wgs-flat-icon flaticon-settings" aria-hidden="true"></i> <?php echo $_lang['config_Setting']; ?> </a></li>
    <li class=""><a href="<?php echo $modulelink; ?>&action=product"><i class="wgs-flat-icon flaticon-tool"></i> <?php echo $_lang['product_Setting']; ?></a></li>
    <li><a href="https://wiki.whmcsglobalservices.com/index.php?title=CloudFlare_Reseller_WHMCS_Module" target="_blank"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['module_doc']; ?></a></li>
</ul>
<div class="container-fluid cf_dashboard">
    <div class="addon_inner_dash">
        <h2 class="ad_title_dash">About</h2>
        <div class="ad_content_sec_dash">
            <div class="add_version_sec_dash">
                <table class="ad_on_table_dash" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td><?php echo $_lang['version']; ?></td>
                            <td align="right">V<?php echo $vars['version']; ?></td>
                        </tr>
                        <tr>
                            <td class="td-color"><?php echo $_lang['license']; ?></td>
                            <td align="right" class="td-color"><?php echo $license_key; ?></span> </td>
                        </tr>
                        <tr>
                            <td><?php echo $_lang['licensestatus']; ?></td>
                            <td align="right"><span class="license <?php echo $class; ?>"><?php echo $getlicense['status']; ?></span></td>
                        </tr>
                        <tr>
                            <td class="td-color"><?php echo $_lang['licenseregemail']; ?></td>
                            <td align="right" class="td-color"><?php echo $getlicense['email']; ?></span> </td>
                        </tr>
                        <tr>
                            <td><?php echo $_lang['licensevaliddomain']; ?></td>
                            <td align="right"><?php echo $getlicense['validdomain']; ?></td>
                        </tr>
                        <tr>
                            <td class="td-color"><?php echo $_lang['author']; ?></td>
                            <td align="right" class="td-color"><a href="http://whmcsglobalservices.com/" target="_blank">WHMCS GLOBAL SERVICES</a></td>
                        </tr>
                        <tr>
                            <td><?php echo $_lang['productname']; ?></td>
                            <td align="right"><b>Cloudflare Reseller Module</b></td>
                        </tr>
                        <tr>
                            <td class="td-color"><?php echo $_lang['lastupdated']; ?></td>
                            <td align="right" class="td-color"> 10 Oct, 2021</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>