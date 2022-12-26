<?php
$dashboardLink = $modulelink;
$osListtLink = $modulelink . '&action=guest_os_list';
$templateLink = $modulelink . '&action=vm_templates';
$ipMapLink = $modulelink . '&action=ip_map';
$osmappingLink = $modulelink . '&action=os_map';
$provisioningSettingLink = $modulelink . '&action=provisioning_setting';
$proxySettingLink = $modulelink . '&action=proxy_setup';
$settingLink = $modulelink . '&action=setting';
$sshSettingLink = $modulelink . '&action=ssh_setting';
$emailtemplate = $modulelink . '&action=emailtemplate';
$serverSetup = $modulelink . '&action=server_setup';
$existingVm = $modulelink . '&action=existing_vm';
$migrateVm = $modulelink . '&action=migrate_vms';
$assignIp = $modulelink . '&action=assign_ip';
$apps = $modulelink . '&action=apps';
$traffic = $modulelink . '&action=traffic';
$changelog = 'https://whmcsglobalservices.com/members/index.php?m=devtracker&p=changelog&id=13';
$requestNewFeatures = 'https://whmcsglobalservices.com/members/index.php?m=devtracker&p=newrequest&id=13';
$logs = $modulelink . '&action=logs';
$moduleDoc = 'http://wiki.whmcsglobalservices.com/index.php?title=VMware_WHMCS_Module';
?>

<div class="add_hdr">
    <a href="https://whmcsglobalservices.com/" class="small_logo" target="_blank"><img src="<?php echo $imgPath; ?>small_logo.png"></a>
    <div class="add_nav">

        <i class="fa fa-list" aria-hidden="true" onmouseover="jQuery(this).next('ul').stop(true, false, true).fadeIn('slow');"></i>

        <ul style="display:none;" onmouseleave="jQuery(this).stop(true, false, true).fadeOut('slow');">
            <li style="border-top: 1px solid #fff;"><a href="<?php echo $dashboardLink; ?>" class="ad_home"><?php echo $LANG['dashboard']; ?></a></li>
            <li><a href="<?php echo $serverSetup; ?>" class="" style="padding: 0;"><i class="fa fa-server" aria-hidden="true"></i><?php echo $LANG['serversetup']; ?></a></li>
            <!--            <li><a href="<?php echo $osListtLink; ?>" class="ad_pr"><?php echo $LANG['guestoslist']; ?></a></li>
            <li><a href="<?php echo $templateLink; ?>" class="ad_email"><?php echo $LANG['vmtemplates']; ?></a></li>-->
            <li><a href="<?php echo $osmappingLink; ?>" style="padding: 0;"><i class="fa fa-map" aria-hidden="true"></i>&nbsp;<?php echo $LANG['vmosmap']; ?></a></li>
            <li><a href="<?php echo $ipMapLink; ?>" class="ad_logs"><?php echo $LANG['ipmapping']; ?></a></li>
            <li><a href="<?php echo $provisioningSettingLink; ?>" class="ad_logs"><?php echo $LANG['provisioning_setting']; ?></a></li>
            <li><a href="<?php echo $proxySettingLink; ?>" class="ad_logs"><?php echo $LANG['proxy_setup']; ?></a></li>
            <li><a href="<?php echo $traffic; ?>" class="" style="padding: 0;"><i class="fas fa-chart-area" aria-hidden="true"></i><?php echo $LANG['trafficusage']; ?></a></li>
            <li><a href="<?php echo $assignIp; ?>" class="" style="padding: 0;" target="_blank"><i class="fas fa-user-check" aria-hidden="true"></i><?php echo $LANG['assignip']; ?></a></li>
            <li><a href="<?php echo $settingLink; ?>" style="padding: 0;"><i class="fa fa-sync" aria-hidden="true"></i><?php echo $LANG['setting']; ?></a></li>
            <li><a href="<?php echo $logs ?>" style="padding: 0;"><i class="fas fa-book-open" aria-hidden="true"></i><?php echo $LANG['logs']; ?></a></li>
            <li><a href="<?php echo $existingVm; ?>" class="" style="padding: 0;"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i><?php echo $LANG['existingvm']; ?></a></li>
            <li><a href="<?php echo $apps; ?>" class="ad_set"><?php echo $LANG['manage_apps']; ?></a></li>
            <li><a href="<?php echo $migrateVm; ?>" class="" style="padding: 0;"><i class="fa fa-arrows-h" aria-hidden="true"></i><?php echo $LANG['migrate_vms']; ?></a></li>
            <li><a href="<?php echo $emailtemplate; ?>" class="mail_icon"><?php echo $LANG['mailtemplatesetting']; ?></a></li>
            <li><a href="<?php echo $moduleDoc; ?>" class="" style="padding: 0;" target="_blank"><i class="fas fa-file-alt" aria-hidden="true"></i><?php echo $LANG['module_doc']; ?></a></li>
        </ul>

    </div>
</div>