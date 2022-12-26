<?php



use Illuminate\Database\Capsule\Manager as Capsule;



$message = '';

global $whmcs;

if (isset($_POST['assign_ip'])) {

    $vmname = $whmcs->get_req_var('vm');

    $ips = $whmcs->get_req_var('ip');

    $serviceid = $whmcs->get_req_var('serviceid');

    foreach ($ips as $ipKey => $ip) {

        if ($ipKey == 0) {

            $sts = 1;

            $dedicatedIp = $ip;
        } else {

            $sts = 2;
        }

        if ($upgrade) {

            $sts = 2;
        }

        try {

            $updatedStatus = Capsule::table('mod_vmware_ip_list')

                ->where('ip', $ip)

                ->update(

                    [

                        'status' => $sts,

                        'forvm' => $vmname,

                    ]

                );

            if ($serviceid) {

                try {

                    $updatedStatus = Capsule::table('tblhosting')

                        ->where('id', $serviceid)->where('dedicatedip', '=', '')

                        ->update(

                            [

                                'dedicatedip' => $dedicatedIp,

                            ]

                        );
                } catch (\Exception $e) {

                    logActivity("couldn't update tblhosting: {$e->getMessage()}");
                }
            }

            $message = '<div class="successbox">' . $LANG['addedsucceessmsg'] . '</div>';
        } catch (\Exception $e) {

            $message = '<div class="errorbox">' . $e->getMessage() . '</div>';

            logActivity("couldn't update mod_vmware_ip_list: {$e->getMessage()}");
        }
    }
}

?>
<script type="text/javascript" language="javascript" src="<?php echo $jsPath; ?>index.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo $jsPath; ?>standalone/selectize.js"></script>
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

                        <h3><?php echo $LANG['assignip']; ?></h3>

                        <p>

                            <?php echo $LANG['vmassignipdesc']; ?>&nbsp;

                        </p>

                    </div>

                    <?php if (isset($message)) echo $message; ?>

                    <form action="" method="post">

                        <input type="hidden" name="serviceid" id="serviceid">

                        <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                            <tbody>

                                <tr>



                                    <td width="15%" class="fieldlabel">

                                        <?php echo $LANG['ipassign_selectvm']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="vm" required="" id="select-vm" onchange="insertSid(this);">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            $pidArr = [];

                                            $fId = [];

                                            foreach (Capsule::table('tblproducts')->where('servertype', 'vmware')->orderby('id', 'ASC')->get() as $product) {

                                                $pidArr[] = $product->id;

                                                $getCfId = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $product->id)->where('fieldname', 'like', '%vm_name%')->first();

                                                $fId[] = $getCfId->id;
                                            }

                                            foreach (Capsule::table('tblhosting')->select('tblcustomfieldsvalues.value', 'tblhosting.id')->join('tblcustomfieldsvalues', 'tblcustomfieldsvalues.relid', '=', 'tblhosting.id')->whereIn('fieldid', $fId)->get() as $vmService) {

                                                if ($vmService->value)

                                                    echo '<option value="' . $vmService->value . '" sid="' . $vmService->id . '">' . $vmService->value . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['selectip']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <select name="ip[]" id="ips" required="" multiple="multiple" style="width: 150px;height: 150px;">

                                            <option value=""><?php echo $LANG['select']; ?></option>

                                            <?php

                                            foreach (Capsule::table('mod_vmware_ip_list')->where('status', '0')->orderby('id', 'ASC')->get() as $ip) {

                                                $ip = (array) $ip;

                                                echo '<option value="' . $ip['ip'] . '">' . $ip['ip'] . '</option>';
                                            }

                                            ?>

                                        </select>

                                    </td>

                                </tr>

                                <tr>

                                    <td colspan="100%">

                                        <div class="form_btn">

                                            <input type="submit" name="assign_ip" value="<?php echo $LANG['submit']; ?>">

                                            &nbsp;&nbsp;

                                            <input type="reset" value="<?php echo $LANG['cancel']; ?>">



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

<script>
    function insertSid(obj) {

        jQuery('#serviceid').val(jQuery('option:selected', obj).attr('sid'));

    }
</script>


<script>
    $('#select-vm').selectize({

        hideSelected: true,

        placeholder: "Select User",

        transitionEnter: true,

        transitionLeave: true

    });
</script>