<?php
use WHMCS\Database\Capsule;

global $whmcs;
$clodflare = new Manage_Cloudflare();

if (isset($_POST['insertsetting'])) {
    $clodflare->insert_settings();
}
if (isset($_POST['getaccount'])) {
    $clodflare->get_accountId();
}
$getdata = $clodflare->get_settings();

$license_key = $getdata->license_key;
$api_url = $getdata->api_url;
if ($api_url == '')
    $api_url = "https://api.cloudflare.com/client/v4/";
$serviceType = $getdata->servicetype;
if ($serviceType == '')
    $serviceType = 'reseller';
$api_key = decrypt($getdata->api_key);
$hosting_apikey = decrypt($getdata->hosting_apikey);
$get_email = $getdata->email;
$pro_plan_price = $getdata->pro_plan_price;
$biz_plan_price = $getdata->biz_plan_price;
$autoregister = $getdata->domain_registrar;

$success = $error = '';

?>

<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/style.css">
<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/fonts.css">
<script type="text/javascript" src="../modules/addons/wgs_cloudflare/assets/js/script.js"></script>
<ul class="nav nav-pills">
    <li><a href="<?php echo $modulelink; ?>"><i class="wgs-flat-icon flaticon-line-graph" aria-hidden="true"></i> <?php echo $_lang['Dashboard']; ?></a></li>
    <li class="active"><a href="<?php echo $modulelink; ?>&action=settings"><i class="wgs-flat-icon flaticon-settings" aria-hidden="true"></i> <?php echo $_lang['config_Setting']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=product"><i class="wgs-flat-icon flaticon-tool"></i> <?php echo $_lang['product_Setting']; ?></a></li>
    <li><a href="https://wiki.whmcsglobalservices.com/index.php?title=CloudFlare_Reseller_WHMCS_Module" target="_blank"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['module_doc']; ?></a></li>
</ul>
<div class="container-fluid links">
    <div class="col-md-12  ">
        <div class="col-md-10">
            <div class="n-base">
                <a href="<?php echo $modulelink; ?>"><?php echo $_lang['Dashboard']; ?></a>&nbsp;/&nbsp; <?php echo $_lang['config_Setting']; ?>
            </div>
        </div>
        <!-- <div class="col-md-2">
           <a href="addonmodules.php?module=wgs_cloudflare" class="btn btn-info pull-right" id="btnback"><?php// echo $_lang['btn-go_back']; ?> </a>
        </div> -->
    </div>
</div>
<div class="container-fluid form">
    <div class="cols-md-12">
        <?php
        if ($_GET['insert'] == 'success') {
            echo '<div class="successbox"> <strong>Success:</strong><br/>
					   Data inserted successfully </div>';
        }
        if ($_GET['insert'] == 'error') {
            echo '<div class="errorbox">  <strong>Error:</strong><br/>
					   Data Coulf not inserted </div>';
        }
        ?>
    </div>
    <form id="form-settings" method="post" action="">
        <div class="row data">
            <!--<div id="title"><h3><?php echo $_lang['label_Configuration_Settings']; ?> </h3></div>-->
            <div class="row">
                <div class="wgs-col-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="Licensekey" name="lc"><?php echo $_lang['License_Key']; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="Licensekey" class="form-control" id="Licensekey" value="<?php echo $license_key; ?>" placeholder="Enter your license key">
                        </div>
                    </div>
                </div>
                <div class="wgs-col-6 text-left">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="servicetype"><?php echo $_lang['servicetype']; ?></label>
                        <div class="col-sm-4">
                            <label><input type="radio" name="servicetype" id="" class="" value="hosting_partner" <?php echo ($serviceType == 'hosting_partner') ? 'checked' : ''; ?>> <?php echo $_lang['cf_hosting_partner']; ?></label>&nbsp;
                            <label><input type="radio" name="servicetype"   class="" value="reseller" <?php echo ($serviceType == 'reseller') ? 'checked' : ''; ?>> <?php echo $_lang['cf_reseller']; ?></label>
                        </div>
                    </div>
                </div>
                <div class="wgs-col-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="apiurl"><?php echo $_lang['API_URL']; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="apiurl" id="apiurl" class="form-control" value="<?php echo $api_url; ?>" placeholder="Enter your api url">
                        </div>
                    </div>
                </div>
                <div id="hosting_partner" <?php echo ($serviceType == 'hosting_partner') ? 'style="display:block"' : 'style="display:none"'; ?> class="servertypeform">
                    <div class="wgs-col-6">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="hostingapikey"><?php echo $_lang['Hosting_API_Key']; ?></label>
                            <div class="col-sm-4">
                                <input type="password" name="hostingapikey" id="hostingapikey" class="form-control" value="<?php echo $hosting_apikey; ?>" placeholder="Enter your hosting api key">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="reseller" <?php echo ($serviceType == 'reseller') ? 'style="display:block"' : 'style="display:none"'; ?> class="servertypeform">
                    <div class="wgs-col-6">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="apikey"><?php echo $_lang['API_Key']; ?></label>
                            <div class="col-sm-4">
                                <input type="password" name="apikey" id="apikey" class="form-control" value="<?php echo $api_key; ?>" placeholder="Enter your api key">
                            </div>
                        </div>
                    </div>
                    <div class="wgs-col-6">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email"><?php echo $_lang['Email']; ?></label>
                            <div class="col-sm-4">
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $get_email; ?>" placeholder="Enter your cloudflare email">
                            </div>
                        </div>
                    </div>
                     <div class="wgs-col-6">
                        <div class="form-group">
                            <label class="control-label col-sm-2" ><?php echo $_lang['enable_domain']; ?></label>
                            <div class="col-sm-4 ctm_label">
                                <label class="switch">
                                <input name="domain_registrar" class="tgl tgl-skewed" id="domain_registrarbtn"   type="checkbox" <?php if($autoregister == 'on'){ echo 'checked'; } ?> >
                                <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wgs-col-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pro_plan_price"><?php echo $_lang['pro_plan_price']; ?></label>
                        <div class="col-sm-4 text-left">
                            <input type="number" min="0" max="999" maxlength="3" name="pro_plan_price" id="pro_plan_price" class="form-control" value="<?php echo $pro_plan_price; ?>" placeholder="Enter price for pro plan">
                            <small><?php echo $_lang['pro_plan_price_desc']; ?></small>
                        </div>
                    </div>
                </div>

                <div class="wgs-col-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="biz_plan_price"><?php echo $_lang['biz_plan_price']; ?></label>
                        <div class="col-sm-4 text-left">
                            <input type="number" min="0" max="999" maxlength="3" name="biz_plan_price" id="biz_plan_price" class="form-control" value="<?php echo $biz_plan_price; ?>" placeholder="Enter price for business plan">
                            <small><?php echo $_lang['pro_plan_price_desc']; ?></small>
                        </div>
                    </div>
                </div>
                <div class="wgs-col-6">
                    <button type="submit" name="insertsetting" class="btn btn-primary settings"><?php echo $_lang['btn-submit']; ?></button>
                </div>
            </div>
        </div>
    </form>
</div>