<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$success = $error = $serverIp =  "";
try {
    if (isset($_POST['proxy_ip']) && $_POST['proxy_ip'] != "") {
        if (Capsule::table("mod_vmware_proxy_setup")->count() > 0)
            Capsule::table("mod_vmware_proxy_setup")->update(["server_ip" => $whmcs->get_req_var('proxy_ip')]);
        else
            Capsule::table("mod_vmware_proxy_setup")->insert(["server_ip" => $whmcs->get_req_var('proxy_ip')]);
        $success = "Your changes have been successfully updated.";
    } elseif (isset($_POST['proxy_ip']) && $_POST['proxy_ip'] == "") {
        $error = "Proxy server IP is required!";
    }
} catch (\Exception $ex) {
    $error = $ex->getMessage();
}

$getServerIp = Capsule::table("mod_vmware_proxy_setup")->first();
if (count($getServerIp) > 0)
    $serverIp = $getServerIp->server_ip;
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

                        <h3><?php echo $LANG['proxy_header_setting']; ?></h3>

                        <p>
                            <?php echo $LANG['proxy_header_text']; ?>&nbsp;
                        </p>

                    </div>
                    <?php

                    if ($success)
                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>' . $success . '</div>';
                    elseif ($error)
                        echo '<div class="errorbox"><strong><span class="title">Failed!</span></strong><br>' . $error . '</div>';
                    ?>
                    <form action="" method="post">

                        <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                            <tbody>

                                <tr>

                                    <td width="15%" class="fieldlabel">
                                        <label for="proxy_ip"><?php echo $LANG['proxy_ip']; ?></label>
                                    </td>

                                    <td class="fieldarea">

                                        <input type="text" value="<?php echo $serverIp; ?>" name="proxy_ip" id="proxy_ip" placeholder="127.0.0.1" required>

                                    </td>

                                </tr>

                                <tr>

                                    <td colspan="100%">

                                        <div class="form_btn">

                                            <input type="submit" name="submit" value="<?php echo $LANG['update_proxy_btn']; ?>">


                                        </div>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </form>
                    <?php

                    if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                        require_once dirname(__DIR__) . '/includes/footer.php';

                    ?>

                </div>
            </div>
        </div>
    </div>
</div>