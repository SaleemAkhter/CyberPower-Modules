<?php

use WHMCS\Database\Capsule;

global $whmcs;
$clodflare = new Manage_Cloudflare();
$getdetail = $clodflare->get_product_settings();
$getSetting = $clodflare->get_settings();
$getservicetype =$getSetting->servicetype;

$get_account_list = $clodflare->get_account_list();

if (isset($_POST['savesetting']) && $_POST['savesetting'] == 'yes') {
    $clodflare->insert_products();
}

$data = $clodflare->get_products();

foreach ($data as $value) {
    $pname = $value->name;
    $pid = $value->id;
    $products .= '<option value="' . $pid . '">' . $pname . '</option>';
}

$featuresuccess = $success = $update = $error = '';
if($getservicetype != 'hosting_partner'){   
if ($get_account_list['result'] == 'error' && $get_account_list['data']['cferrorcode'] != '')
    $error = '<div class="errorbox"> <strong>Error:</strong><br/>Errorcode: ' . $get_account_list['data']['cferrorcode'] . ', Error Message: ' . $get_account_list['data']['apierror'] . '</div>';
}
?>

<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/style.css">
<link rel="stylesheet" href="../modules/addons/wgs_cloudflare/assets/css/fonts.css">
<!--<script type="text/javascript" src="../modules/addons/wgs_cloudflare/assets/js/script.js"></script>-->
<ul class="nav nav-pills">
    <li><a href="<?php echo $modulelink; ?>"><i class="wgs-flat-icon flaticon-line-graph" aria-hidden="true"></i> <?php echo $_lang['Dashboard']; ?></a></li>
    <li><a href="<?php echo $modulelink; ?>&action=settings"><i class="wgs-flat-icon flaticon-settings" aria-hidden="true"></i> <?php echo $_lang['config_Setting']; ?></a></li>
    <li class="active"><a href="<?php echo $modulelink; ?>&action=product"><i class="wgs-flat-icon flaticon-tool"></i> <?php echo $_lang['product_Setting']; ?></a></li>
    <li><a href="https://wiki.whmcsglobalservices.com/index.php?title=CloudFlare_Reseller_WHMCS_Module" target="_blank"><i class="wgs-flat-icon flaticon-file"></i> <?php echo $_lang['module_doc']; ?></a></li>
</ul>
<div class="container-fluid links">
    <div class="col-md-12  ">
        <div class="col-md-10">
            <div class="n-base">
                <a href="<?php echo $modulelink; ?>"> <?php echo $_lang['Dashboard']; ?></a>&nbsp;/&nbsp; <?php echo $_lang['product_Setting']; ?>
            </div>
        </div>
        
    </div>
</div>

<div class="container-fluid" id="product-container">
    <?php

    if ($_GET['featureUpdate'] == 'success') {
        echo '<div class="successbox"> <strong>Success:</strong><br/>Features Updated Successfully.</div>';
    }
    if ($_GET['feature'] == 'success') {
        echo '<div class="successbox"> <strong>Success:</strong><br/>Features Added Successfully.</div>';
    }
    if ($_GET['feature'] == 'error') {
        echo '<div class="errorbox"> <strong>Error:</strong><br/>Features could not added! .</div>';
    }
    if ($_GET['product'] == 'success') {
        echo '<div class="successbox"> <strong>Success:</strong><br/>Product inserted Successfully! .</div>';
    }
    if ($_GET['product'] == 'update') {
        echo '<div class="successbox"> <strong>Success:</strong><br/>Product Updated Successfully! .</div>';
    }
    if ($error) {
        echo $error;
    }
    ?>
    <div class="row">
        <div id="response_data"></div>
    </div>

    <div class="row tbl">
        <div class="col-md-12">
 
            <div class="hidden_p_text">
                <ul>
                    <p><?php echo $_lang['important_note']; ?></p>
                    <li>1. <?php echo $_lang['important_note_text1']; ?></li>
                    <li>2. <?php echo $_lang['important_note_text2']; ?></li>
                    <li>3. <?php echo $_lang['important_note_text3']; ?></li>
                    <li>4. <?php echo $_lang['important_note_text4']; ?></li>
                    <li>5. <?php echo $_lang['important_note_text5']; ?></li>
                    <li></li>
                </ul>

            </div>
        </div>
        <table class="table table-striped table-bordered text-capitalize" id="datatbl">
            <thead>
                <tr>
                    <th width="15%"><?php echo $_lang['table-product_name']; ?></th>
                    <th width="13%"><?php echo $_lang['table-account_type']; ?></th>
                    <th width="9%"><?php echo $_lang['table-enable_member']; ?></th>
                    <th width="6%"><?php echo $_lang['table-create_user']; ?></th>
                    <?php  if($getservicetype != 'hosting_partner'){ echo "<th width='9%' >".$_lang['table-userlist']."</th>";}?> 
                    <th width="8%"><?php echo $_lang['table-plan']; ?></th>
                    <th width="9%"><?php echo $_lang['table-zone_type']; ?></th>
                    <th width="4%"><?php echo $_lang['dns_ip']; ?></th>
                    <th width="2%"><?php echo $_lang['noofdomains']; ?></th>
                    <th width="6%"><?php echo $_lang['proxy']; ?></th>
                    <th width="6%"><?php echo $_lang['table-status']; ?></th>
                    <th width="2%"><?php echo $_lang['table-features']; ?></th>
                    <th width="11%"><?php echo $_lang['table-action']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $userTypeDisabled = $userTypetitle = $memeberDisabled = $memberTitle = '';
                if ($getSetting->servicetype == 'hosting_partner') {
                    $userTypeDisabled = 'disabled="disabled"';
                    $memeberDisabled = "disabled";
                    $userDisabled = "disabled";
                    $userTypetitle = $memberTitle = 'title="Only For CF Reseller Account"';
                } 

                foreach ($data as $key => $value) {

                    $productid = $value['productid'];
                    $plan = $value['plan'];
                    $member = $value['member'];
                    $user = $value['user'];
                    $accountid = $value['accountid'];
                    $usertype = $value['usertype'];
                    $ztype = $value['ztype'];
                    $dnsip = $value['dnsip'];
                    $domains = $value['domains'];
                    $status = $value['status'];
                    $proxy = $value['proxy'];

                    $acc_list = '';
                    foreach ($get_account_list['result'] as $val) {
                        $accId = $val['id'];
                        $accname = $val['name'];
                        $acc_list .= '<option value="' . $accId . '" ' . (($accId == $accountid) ? 'selected' : '') . '>' . $accname . '</option>';
                    }

                    echo '<tr><form id="form-update-product" method="post"><input type="hidden" name="idpsetting" value="' . $productid . '" >';
                    echo '<td>' . $value['name'] . '</td>';
                    echo '<td>	<select name="mtype" class="form-control" ' . $userTypeDisabled . ' ' . $userTypetitle . '>
					<option value="standard" ' . (($usertype == 'standard') ? 'selected' : '') . '>Standard</option>
					<option value="enterprise" ' . (($usertype == 'enterprise') ? 'selected' : '') . '>Enterprise</option>
					</select> </td>';
                    echo '<td ' . $memberTitle . ' align="center"> <input name="member" class="tgl tgl-skewed" id="member' . $key . '" type="checkbox" ' . (($member == '1') ? 'checked' : '') . ' style="display:none;">
                            <label class="tgl-btn ' . $memeberDisabled . ' ' . (($member == '1') ? 'selected' : 'deselected') . '" data-tg-off="OFF" data-tg-on="ON" for="member' . $key . '"></label>
                    <!--label class="switch">
					<input name="member" class="tgl tgl-skewed" id=" "   type="checkbox" ' . (($member == '1') ? 'checked' : '') . '>
					<span class="slider round"></span>
					</label--> </td>';
                    echo '<td  align="center"> <input name="user" class="tgl tgl-skewed" id="user' . $key . '" type="checkbox" ' . (($user == '1') ? 'checked' : '') . ' style="display:none;">
                            <label class="tgl-btn ' . $userDisabled . ' ' . (($user == '1') ? 'selected' : 'deselected') . '" data-tg-off="OFF" data-tg-on="ON" for="user' . $key . '"></label>
                      </td>';

                    if($getservicetype != 'hosting_partner'){
                       echo '<td><select   name="username" class="form-control" ' . $userDisabled . ' >
                     ' . $acc_list . '
                    </select> </td>'; 
                    } 

                    echo '<td><select id="pdt" name="plan" class="form-control" >
					<option value="FREE"  ' . (($plan == 'FREE') ? 'selected' : '') . '>Free</option>
					<option value="PARTNERS_PRO" ' . (($plan == 'PARTNERS_PRO') ? 'selected' : '') . ' >Pro</option>
					<option value="PARTNERS_BIZ"  ' . (($plan == 'PARTNERS_BIZ') ? 'selected' : '') . ' >Business</option>
					<option value="PARTNERS_ENT" ' . (($plan == 'PARTNERS_ENT') ? 'selected' : '') . ' >Enterprise</option>
					</select> </td>';
                    echo '<td><select name="ztype"class="form-control">
					<option value="partial" ' . (($ztype == 'partial') ? 'selected' : '') . '>Partial Zone Setup (C Name)</option>
					<option value="full" ' . (($ztype == 'full') ? 'selected' : '') . '>Full Zone Setup (DNS)</option>
					</select> </td>';
                    echo '<td><input type="text" name="dnsip" class="form-control" value="' . $dnsip . '"></td>';
                    echo '<td><input type="number" name="domains" class="form-control" value="' . $domains . '"></td>';
                    echo '<td align="center"> <input name="proxy" class="tgl tgl-skewed" id="proxy' . $key . '" type="checkbox" ' . (($proxy == '1') ? 'checked' : '') . ' style="display:none;">
                            <label class="tgl-btn ' . (($proxy == '1') ? 'selected' : 'deselected') . '" data-tg-off="OFF" data-tg-on="ON" for="proxy' . $key . '"></label>
                    <!--label class="switch" >
					<input name="proxy" id=" "   type="checkbox" ' . (($proxy == '1') ? 'checked' : '') . '>
					<span class="slider round"></span>
					</label--> </td>';
                    echo '<td align="center"> <input name="Status" class="tgl tgl-skewed" id="Status' . $key . '" type="checkbox" ' . (($status == '1') ? 'checked' : '') . ' style="display:none;">
                            <label class="tgl-btn ' . (($status == '1') ? 'selected' : 'deselected') . '" data-tg-off="OFF" data-tg-on="ON" for="Status' . $key . '"></label>
                            <!--label class="switch" >
					<input name="Status" id=" "   type="checkbox" ' . (($status == '1') ? 'checked' : '') . '>
					<span class="slider round"></span>
					</label--> </td>';
                    echo '<td>' . $value['features'] . '</td>';
                    echo '<input name="savesetting" type="hidden" value="yes">';
                    echo '<td><a href="addonmodules.php?module=wgs_cloudflare&action=features&id=' . $productid . '" class="btn btn-info "  title="' . $_lang['table-btn-edit'] . '"><i class="fas fa-edit"></i></a> <button class="btn btn-info" name="save" title="' . $_lang['table-btn-save'] . '"><i class="fas fa-save"></i></button></td>';
                    echo '</form></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatbl').DataTable({
            "oLanguage": {
                "sLengthMenu": "Display _MENU_ records",
            }
        });
        jQuery('.tgl-btn').click(function() {
            var labelID = jQuery(this).attr('for');
            if (jQuery("#" + labelID).prop('checked'))
                jQuery(this).removeClass('selected').addClass('deselected');
            else
                jQuery(this).removeClass('deselected').addClass('selected');
        });
    });
</script>