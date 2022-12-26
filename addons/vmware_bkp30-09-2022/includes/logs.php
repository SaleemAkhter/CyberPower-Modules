<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (isset($_GET['action']) && $_GET['action'] == "logs" && isset($_GET['clear']) && $_GET['clear'] == "true") {
    Capsule::table("mod_vmware_logs")->delete();
    $_SESSION['logclrsuccess'] = true;
    header("location:" . $modulelink . "&action=logs");
    exit();
}
$logs = [];
$totalRecordPerPage = 50;
$totalPageShow = 0;
$page = $whmcs->get_req_var('page') < 1 ? 1 : $whmcs->get_req_var('page');
$start = ($page - 1) * $show_per_page;
if ($vars['enable_log'] == "on") {
    $totalRecords = Capsule::table("mod_vmware_logs")->count();
    $totalPageShow = ceil($totalRecords / $totalRecordPerPage);
    global $whmcs;
    $logsArr = Capsule::table("mod_vmware_logs")->limit($totalRecordPerPage)->offset(($page - 1) * $totalRecordPerPage)->orderBy('id', 'desc')->get();
}
?>



<div id="wrapper">

    <div class="addon_container">

        <div class="ad_content_area">

            <?php

            if (file_exists(dirname(__DIR__) . '/includes/header.php'))

                require_once dirname(__DIR__) . '/includes/header.php';

            ?>

            <div class="addon_inner">
                <div class="currenttime"><?php echo $LANG['current_time']; ?> <?php echo toMySQLDate(date("d/m/Y H:i:s")); ?></div>
                <div class="dashoboard-container">
                    <div class="clrlog"><a href="<?php echo $modulelink; ?>&action=logs&clear=true" onclick="return confirm('Are you sure to clear the logs?');"><?php echo $LANG['log_clear']; ?></a></div>
                    <?php
                    if (isset($_SESSION['logclrsuccess']) && $_SESSION['logclrsuccess'] == true) {

                        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Logs have been successfully cleared.</div>';
                        unset($_SESSION['logclrsuccess']);
                    }

                    ?>
                    <?php

                    if ($vars['enable_log'] != "on") {

                        echo '<div class="lognotenable">You have not enable this setting "<b>Enable Log</b>" under module configuration setting. <a target="_blank" href="configaddonmods.php#vmware">Click Here</a> to enable it.</div>';
                    }

                    ?>

                    <div class="tablebg">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">

                            <tbody>

                                <tr>
                                    <td width="50%" align="left"><?php echo str_replace(
                                                                        array("%s%", "%s1%", "%s2%"),
                                                                        array($totalRecords, $page, $totalPageShow),
                                                                        $LANG['record_found_text']
                                                                    ); ?></td>

                                    <td width="50%" align="right"><?php echo $LANG['jump_to']; ?>
                                        <form action="" method="post">
                                            <select name="page" onchange="submit()" id="pagination">

                                                <?php
                                                for ($i = 1; $i <= $totalPageShow; $i++) {

                                                    if ($page == $i)
                                                        $sel = 'selected="selected"';
                                                    else
                                                        $sel = '';
                                                ?>

                                                    <option value="<?php echo $i; ?>" <?php echo $sel; ?>><?php echo $i; ?></option>

                                                <?php
                                                }
                                                ?>

                                            </select>

                                            <input type="submit" value="<?php echo $LANG['go']; ?>" class="btn btn-xs btn-default">
                                        </form>
                                    </td>

                                </tr>

                            </tbody>

                        </table>
                        <table id="logstable" class="datatable partnerdetail_table table table-striped table-bordered" cellspacing="0" width="100%" style="word-wrap:break-word; table-layout: fixed;">

                            <thead>

                                <tr>

                                    <th><?php echo $LANG['log_vmname']; ?></th>

                                    <th><?php echo $LANG['log_date']; ?></th>

                                    <th><?php echo $LANG['log_description']; ?></th>

                                    <th><?php echo $LANG['log_username']; ?></th>

                                    <th><?php echo $LANG['log_ip']; ?></th>

                                    <th><?php echo $LANG['log_status']; ?></th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                if ($vars['enable_log'] == "on") {

                                    if (count($logsArr) > 0) {
                                        print_r($loglogsArrs);
                                        foreach ($logsArr as $log) {

                                            $log = (array) $log;

                                ?>

                                            <tr>

                                                <td><a href="clientsservices.php?id=<?php echo $log['sid']; ?>" target="_blank"><?php echo $log['vmname']; ?></a></td>

                                                <td><?php $date = date('d/m/Y H:i:s', strtotime($log['date']));
                                                    echo toMySQLDate($date); ?></td>

                                                <td><?php echo $log['description']; ?></td>

                                                <td><?php echo ucfirst($log['username']); ?></td>

                                                <td><?php echo $log['ip']; ?></td>

                                                <td><?php echo $log['status']; ?></td>

                                            </tr>

                                        <?php

                                        }
                                    } else {

                                        ?>

                                        <tr>

                                            <td colspan="6">No Record Found.</td>

                                        </tr>

                                    <?php

                                    }
                                } else {

                                    ?>

                                    <tr>

                                        <td colspan="6">No Record Found.</td>

                                    </tr>

                                <?php

                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                    <?php

                    if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                        require_once dirname(__DIR__) . '/includes/footer.php';

                    ?>

                </div>

            </div>

        </div>

    </div>

</div>

<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" type="text/css">

<script src=https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> </script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

<script>
    // jQuery(document).ready(function() {

    //     jQuery('#logstable').DataTable({
    //         "pageLength": 25,
    //         "order": [
    //             [1, "desc"]
    //         ],
    //     });

    // });
</script>