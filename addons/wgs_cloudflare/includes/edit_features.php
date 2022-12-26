<?php

use WHMCS\Database\Capsule;

global $whmcs;
$clodflare = new Manage_Cloudflare();

$productid = $_GET['id'];
$features = $clodflare->getfeatures($productid);

$input = preg_replace("/((\r?\n)|(\r\n?))/", ',', $features);

$pieces = explode(',', $input);

foreach ($pieces as $name) {
    $name = trim($name, " ");
    if ($name == 'overview') {
        $checked = 'checked';
    }
    if ($name == 'analytics') {
        $analytics = 'checked';
    }
    if ($name == 'dns') {
        $dns = 'checked';
    }
    if ($name == 'crypto') {
        $crypto = 'checked';
    }
    if ($name == 'firewall') {
        $firewall = 'checked';
    }
    if ($name == 'speed') {
        $speed = 'checked';
    }
    if ($name == 'caching') {
        $caching = 'checked';
    }
    if ($name == 'scrape shield') {
        $scrapeshield = 'checked';
    }
    if ($name == 'plan') {
        $plan = 'checked';
    }
}
if (isset($_POST['features'])) {
    $clodflare->insert_features();
}
?>

<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/style.css">
<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/fonts.css">
<!--<script type="text/javascript" src="../modules/addons/wgs_cloudflare/assets/js/script.js"></script>-->
<ul class="nav nav-pills">

    <li><a href="<?php echo $modulelink; ?>"><i class="wgs-flat-icon flaticon-line-graph" aria-hidden="true"></i> <?php echo $_lang['Dashboard']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=settings"><i class="wgs-flat-icon flaticon-settings" aria-hidden="true"></i> <?php echo $_lang['config_Setting']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=product"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['product_Setting']; ?></a></li>
</ul>
<div class="container-fluid links">
    <div class="col-md-12  ">
        <div class="col-md-10">
            <div class="n-base">
                <a href="addonmodules.php?module=wgs_cloudflare"><?php echo $_lang['Dashboard']; ?></a>&nbsp;/&nbsp; Features add&nbsp;/&nbsp;
            </div>
        </div>
        <div class="col-md-2">
            <a href="addonmodules.php?module=wgs_cloudflare&action=product" class="btn btn-info pull-right" id="btnback"><?php echo $_lang['btn-go_back']; ?></a>
        </div>
    </div>
</div>
<div class="container-fluid" id="edit-features-container">

    <form method="post">
        <input type="hidden" name="pid" value="<?php echo $productid; ?>">
        <div class="row col-lg-3">
            <div class="feature-box">
                <label><?php echo $_lang['overview']; ?></label>
                <input name="featureget[]" value="overview" class="tgl tgl-skewed" id="overview" type="checkbox" <?php echo ((in_array('overview', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('overview', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="overview"></label>
                <!--label class="switch feature ">
                    <input type="checkbox" name="featureget[]" value="overview" <?php echo $checked; ?> >
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['analytics']; ?></label>
                <input name="featureget[]" value="analytics" class="tgl tgl-skewed" id="analytics" type="checkbox" <?php echo ((in_array('analytics', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('analytics', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="analytics"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]" value="analytics" <?php echo $analytics; ?> >
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['dns']; ?></label>
                <input name="featureget[]" value="dns" class="tgl tgl-skewed" id="dns" type="checkbox" <?php echo ((in_array('dns', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('dns', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="dns"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]"  value="dns" <?php echo $dns; ?> >
                    <span class="slider round"></span>
                </label-->
            </div>
        </div>
        <div class="row col-lg-3">
            <div class="feature-box">
                <label><?php echo $_lang['cryto']; ?></label>
                <input name="featureget[]" value="crypto" class="tgl tgl-skewed" id="crypto" type="checkbox" <?php echo ((in_array('crypto', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('crypto', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="crypto"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]" value="crypto" <?php echo $crypto; ?>>
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['firewall']; ?></label>
                <input name="featureget[]" value="firewall" class="tgl tgl-skewed" id="firewall" type="checkbox" <?php echo ((in_array('firewall', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('firewall', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="firewall"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]" value="firewall"  <?php echo $firewall; ?>>
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['speed']; ?></label>
                <input name="featureget[]" value="speed" class="tgl tgl-skewed" id="speed" type="checkbox" <?php echo ((in_array('speed', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('speed', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="speed"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]" value="speed" <?php echo $speed; ?>>
                    <span class="slider round"></span>
                </label-->
            </div>
        </div>
        <div class="row col-lg-3">
            <div class="feature-box">
                <label><?php echo $_lang['caching']; ?></label>
                <input name="featureget[]" value="caching" class="tgl tgl-skewed" id="caching" type="checkbox" <?php echo ((in_array('caching', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('caching', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="caching"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]"  value="caching" <?php echo $caching; ?>>
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['scrape_shield']; ?></label>
                <input name="featureget[]" value="scrapeshield" class="tgl tgl-skewed" id="scrapeshield" type="checkbox" <?php echo ((in_array('scrapeshield', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('scrapeshield', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="scrapeshield"></label>
                <!--label class="switch feature" >
                    <input type="checkbox" name="featureget[]" value="scrape shield" <?php echo $scrapeshield; ?>> 
                    <span class="slider round"></span>
                </label-->
            </div><br>
            <div class="feature-box">
                <label><?php echo $_lang['feature_Plan']; ?></label>
                <input name="featureget[]" value="plan" class="tgl tgl-skewed" id="plan" type="checkbox" <?php echo ((in_array('plan', $pieces)) ? 'checked' : ''); ?> style="display:none;">
                <label class="tgl-btn custom_swtch <?php echo ((in_array('plan', $pieces)) ? 'selected' : 'deselected'); ?>" data-tg-off="OFF" data-tg-on="ON" for="plan"></label>
                <!--label class="switch feature">
                    <input type="checkbox" name="featureget[]" value="plan" <?php echo $plan; ?>>
                    <span class="slider round"></span>
                </label-->
            </div>
        </div>
        <!--        <div class="row col-lg-3">
                    <div class="feature-box">
                        <label><?php echo $_lang['dns_ip']; ?></label>
                        <label class="switch feature">
                            <input type="checkbox" name="dnsip[]"  value="dnsip" <?php echo $dnsip; ?>>
                            <span class="slider round"></span>
                        </label
                    </div>
                </div>-->
        <div class="row col-lg-12">
            <button id=" " class="btn btn-info" name="features"><?php echo $_lang['btn-features-save']; ?></button>
        </div>
    </form>

</div>
<script>
    $(document).ready(function() {
        jQuery('.tgl-btn').click(function() {
            var labelID = jQuery(this).attr('for');
            if (jQuery("#" + labelID).prop('checked'))
                jQuery(this).removeClass('selected').addClass('deselected');
            else
                jQuery(this).removeClass('deselected').addClass('selected');
        });
    });
</script>