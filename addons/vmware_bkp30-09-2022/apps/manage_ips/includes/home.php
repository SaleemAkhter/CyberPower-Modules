<link href="../modules/addons/vmware/apps/<?php echo $app; ?>/css/style.css" rel="stylesheet">
<script src="../modules/addons/vmware/apps/<?php echo $app; ?>/js/script.js" type="text/javascript"></script>

<div id="wrapper">

    <div class="addon_container">
        <div class="ad_content_area">

            <?php
            global $whmcs;
            if (file_exists(dirname(__DIR__) . '/includes/header.php'))
                require_once dirname(__DIR__) . '/includes/header.php';
            $tab = $whmcs->get_req_var("tab");
            if (empty($tab))
                $tab = 'key_setup';
            ?>
            <div class="addon_inner">
                <div class="dashoboard-container">
                    <div class="vmware_heading">
                        <?php
                        if ($tab == 'key_setup') {
                            ?>
                            <h3><?php echo $LANG['manage_apps']; ?></h3>
                            <p>
                                <?php echo $LANG['appsdesc']; ?>&nbsp;
                            </p>
                        <?php } elseif ($tab == 'imap_setting') { ?>
                            <h3><?php echo $LANG['imap_setting']; ?></h3>
                            <p>
                                <?php echo $LANG['imap_setting_desc']; ?>&nbsp;
                            </p>
                        <?php } ?>
                    </div>

                    <div class="vmosmap_button">
                        <a href="<?php echo $applink . '&tab=key_setup'; ?>"><button class="<?php echo ($tab == 'key_setup') ? 'active' : ''; ?>"><?php echo $LANG['key_setup']; ?></button></a>
                        <a href="<?php echo $applink . '&tab=imap_setting'; ?>"><button class="<?php echo ($tab == 'imap_setting') ? 'active' : ''; ?>"><?php echo $LANG['imap_setting']; ?></button></a>
                    </div>
                </div>
                <?php
                if (!empty($tab))
                    include_once(__DIR__ . '/' . $tab . '.php');
                ?>
                <?php
                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))
                    require_once dirname(__DIR__) . '/includes/footer.php';
                ?>
            </div>
        </div>
    </div>
</div>