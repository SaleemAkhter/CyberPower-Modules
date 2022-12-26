<?php

use Illuminate\Database\Capsule\Manager as Capsule;
?>

<div id="wrapper">
    <div class="addon_container">
        <div class="ad_content_area">
            <?php
            if (file_exists(dirname(__DIR__) . '/includes/header.php'))
                require_once dirname(__DIR__) . '/includes/header.php';
            ?>
            <div class="addon_inner">
                <div class="dashoboard-container">
                    <div class="vmware_heading">
                        <h3><?php echo $LANG['vmosmap']; ?></h3>
                        <p>
                            <?php echo $LANG['vmosmapdesc']; ?>&nbsp;
                            <?php echo $LANG['vmosmapdesclink']; ?>&nbsp;
                            <?php echo $LANG['vmosmapiso']; ?>
                            <?php echo $LANG['vmclickand']; ?>
                            <?php echo $LANG['vmosmapclone']; ?>
                            <?php echo $LANG['vmclickplease']; ?>&nbsp;
                            <a href="#"><?php echo $LANG['vmclickhere']; ?></a>
                        </p>
                    </div>
                    <div class="vmosmap_button">
                        <a href="<?php echo $modulelink . '&action=guest_os_list'; ?>"><button><?php echo $LANG['vmosmapiso']; ?></button></a>
                        <a href="<?php echo $modulelink . '&action=vm_templates'; ?>"><button><?php echo $LANG['vmosmapclone']; ?></button></a>
                    </div>
                </div>
                <?php
                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))
                    require_once dirname(__DIR__) . '/includes/footer.php';
                ?>
            </div>
        </div>
    </div>
</div>