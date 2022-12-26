<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");
$error = $success = $deleteSuccess = $verifyId = '';

if (isset($_POST['generate_key']) && !empty($_POST['generate_key'])) {

    include_once __DIR__ . "/generate_key.php";
} elseif (isset($_GET['verify']) && $_GET['verify'] == 'true') {

    $verifyId = $whmcs->get_req_var('id');
    include_once __DIR__ . "/generate_key.php";
}
$accountEmail = $whmcs->get_req_var('account_number');
$success = $whmcs->get_req_var('success');
$delete = $whmcs->get_req_var('delete');
$deleteId = $whmcs->get_req_var('id');
if (!empty($accountEmail) && !empty($success)) {
    Capsule::table('mod_ovh_manage_apps')->where('account_number', $accountEmail)->update(['status' => 'verified']);
}
if (!empty($delete) && !empty($deleteId)) {
    if (Capsule::table('mod_ovh_manage_apps')->where('id', $deleteId)->count() == 1) {
        Capsule::table('mod_ovh_manage_apps')->where('id', $deleteId)->delete();
        $deleteSuccess = true;
    }
}
?>

<style>
    table.form td.fieldarea {
        width: 85%;
    }
</style>
<?php
if (!empty($error)) {
    echo '<div class="errorbox"><strong><span class="title">Error!</span></strong><br>' . $error . '.</div>';
} elseif (!empty($success)) {
    echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your account have been authorised.</div>';
} elseif (!empty($deleteSuccess)) {
    echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your account have been deleted.</div>';
}
?>

<form action="<?php echo $applink; ?>&tab=key_setup" method="post" id="" style="">
    <table width="100%" border="0" cellspacing="2" cellpadding="3" class="form partner-form">
        <tbody>
            <tr>
                <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['choosecompany']; ?></td><td class="fieldarea">
                    <select name="service_provider" id="set_service_provider">
                        <option value="soyoustart">Soyoustart</option>
                        <option value="ovh">Ovh</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['serverlocation']; ?></td><td class="fieldarea">
                    <select name="location" id="server_location">
                        <option value="europe">Europe</option>
                        <option value="canada">Canada</option>
                        <option value="us">US</option>
                    </select>     
                    &nbsp;  &nbsp; <?php echo $LANG['createapp']; ?> <a id="set_ser_pro" href="https://eu.api.soyoustart.com/createApp/" target="_blank">App</a>. <?php echo $LANG['createapp2']; ?>. </td>
            </tr>
            <tr>
                <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['applicationkey']; ?></td><td class="fieldarea"><input id="application_key_so" type="text" size="51" required="required" value="" name="application" placeholder="Enter application key"></td>
            </tr>
            <tr>
                <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['secretkey']; ?></td><td class="fieldarea"><input id="secret_key_so" type="text" value="" size="51" required="required" name="secret" placeholder="Enter secret key"></td>
            </tr> 
            <tr>
                <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['user_email']; ?></td><td class="fieldarea"><input id="accountnumber" type="text" value="" size="51" name="account_number" placeholder="Enter User Name"><span class="err" id="0"></span></td>
            </tr>	
            <tr>
                <td colspan="100%">
                    <div class="form_btn">
                        <input type="submit" name="generate_key" value="<?php echo $LANG['generate_key']; ?>">
                        &nbsp;&nbsp;

                        <input type="reset" onclick="jQuery('#temp_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<table id="employeetable" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
    <thead>
        <tr>
            <th width="5%"><a href="#" class="link">#<?php echo $LANG['id']; ?></a></th>                        
            <th width="8%"><a href="#" class="link"><?php echo $LANG['company']; ?></a></th>
            <th width="25%"><a href="#" class="link"><?php echo $LANG['applicationkey']; ?></a></th>
            <th width="25%"><a href="#" class="link"><?php echo $LANG['secretkey']; ?></a></th>
            <th width="10%"><?php echo $LANG['location']; ?></th>
            <th width="15%"><?php echo $LANG['accountuser']; ?></th>
            <th width="7%"><?php echo $LANG['accountstatus']; ?></th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = Capsule::table('mod_ovh_manage_apps')->get();
        foreach ($result as $data1) {
            ?>
            <tr title="<?php echo $LANG['consumerkey']; ?> : <?php echo $data1->consumer_key; ?>">
                <td><?php echo $data1->id; ?></td>                            
                <td><a href="<?php echo $data1->location; ?>"><?php echo $data1->api_service_provider; ?></a></td>
                <td><?php echo $data1->application_key; ?></td>
                <td><?php echo $data1->secret_key; ?></td>
                <td><?php
                    if (strlen($data1->service_location) > 2) {
                        echo substr($data1->service_location, 0, -4);
                    } else {
                        echo $data1->service_location;
                    }
                    ?></a></td>
                <td><?php echo $data1->account_number; ?></a></td>
                <td><?php echo ($data1->status == 'pending') ? '<font color="red">' . $LANG['unverified'] . '</font>' : '<font color="green">' . $LANG['verified'] . '</font>'; ?></td>
                <td>
                    <?php if ($data1->status == 'pending') { ?>
                        <a href="<?php echo $applink; ?>&tab=key_setup&verify=true&id=<?php echo $data1->id; ?>"><img src="../modules/addons/vmware/images/tick.png" width="16" height="16" border="0" title="<?php echo $LANG['verifiedtitle']; ?>"></a>&nbsp;
                    <?php } ?>
                    <a href="<?php echo $applink; ?>&tab=key_setup&delete=true&id=<?php echo $data1->id; ?>" onClick="return confirm('Are you sure want to delete <?php echo "#" . $data1->id ?>?');"><img src="images/delete.gif" width="16" height="16" border="0" title="Delete"></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>