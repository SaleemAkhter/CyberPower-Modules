<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

if (isset($_GET['addimap']) && $_GET['addimap'] == 'true') {
    include_once __DIR__ . '/add_imap.php';
} else {
    $error = $success = $deleteSuccess = '';
    try {
        global $whmcs;
        $accountUser = $whmcs->get_req_var('userAccount');
        $hostname = $whmcs->get_req_var('hostname');
        $username = $whmcs->get_req_var('username');
        $portnumber = $whmcs->get_req_var('portnumber');
        $ssltype = $whmcs->get_req_var('ssltype');
        $password = $whmcs->get_req_var('password');
        $language = $whmcs->get_req_var('language');
        $imapId = $whmcs->get_req_var('imapid');
        if (isset($_POST['action_perform']) && $_POST['action_perform'] == 'imap_config') {
            if (empty($accountUser))
                $error = 'Account User is required!';
            if (empty($hostname))
                $error = 'Hostname is required!';
            elseif (empty($portnumber))
                $error = 'Port is required!';
            elseif (empty($username))
                $error = 'username is required!';
            elseif (empty($password))
                $error = 'Password is required!';
            $queryCheckUser = Capsule::table('mod_ovh_imap')->where('account_user', $accountUser)->count();
            $data = ['soyouimaphost' => $hostname,
                'soyouimapuser' => $username,
                'soyouimapport' => $portnumber,
                'soyouimapssl' => $ssltype,
                'account_user' => $accountUser,
                'language' => $language];
            if (!empty($imapId)) {
                if (substr($password, 0, 6) == '******') {
                    $getExistingPw = Capsule::table('mod_ovh_imap')->where('id', $imapId)->first();
                    $data = array_merge($data, ['soyouimappass' => $getExistingPw->soyouimappass]);
                } else {
                    $data = array_merge($data, ['soyouimappass' => encrypt($password)]);
                }
            } else
                $data = array_merge($data, ['soyouimappass' => encrypt($password)]);

            if ($queryCheckUser == 0) {
                Capsule::table('mod_ovh_imap')->insert($data);
            } else {
                Capsule::table('mod_ovh_imap')->where('id', $imapId)->update($data);
            }
            $success = true;
        }
        if (!empty($_GET['deleteAccount']) && !empty($_GET['deleteAccount'])) {
            $accountUser = $whmcs->get_req_var('deleteAccount');
            $queryCheckUser = Capsule::table('mod_ovh_imap')->where('id', $accountUser)->count();
            if ($queryCheckUser > 0) {
                Capsule::table('mod_ovh_imap')->where('id', $accountUser)->delete();
                $deleteSuccess = true;
            } else
                $error = 'Account already deleted';
        }
    } catch (Exception $ex) {
        $error = $ex->getMessage();
    }
    ?>
    <?php
    if (!empty($error)) {
        echo '<div class="errorbox"><strong><span class="title">Error!</span></strong><br>' . $error . '.</div>';
    } elseif (!empty($success)) {
        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your setting have been saved.</div>';
    } elseif (!empty($deleteSuccess)) {
        echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your account have been deleted.</div>';
    }
    ?>
    <div class="add_imap">            
        <a href="<?php echo $applink; ?>&tab=imap_setting&addimap=true" style="float: right; margin-bottom:10px;" class="btn btn-success"><?php echo $LANG['addimapsetting']; ?></a>
    </div>
    <h1><?php echo $LANG['webmail_imaps']; ?></h1>
    <table id="employeetable" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3" style="clear:both;">
        <thead>
            <tr>                    
                <th> &nbsp;<?php echo $LANG['accountuser']; ?></th>
                <th> &nbsp;<?php echo $LANG['username']; ?></th>
                <th> &nbsp;<?php echo $LANG['incomingmailservername']; ?></th>
                <th> &nbsp;<?php echo $LANG['portnumber']; ?></th>
                <th> &nbsp;<?php echo $LANG['ssltype']; ?></th>
                <th> &nbsp;<?php echo $LANG['status']; ?></th>
                <th> &nbsp;<?php echo $LANG['language']; ?></th>
                <th width="60px"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $imapDatas = Capsule::table('mod_ovh_imap')->get();

            foreach ($imapDatas as $imapData) {
                ?>
                <tr>                
                    <td><?php echo $imapData->account_user; ?></td>
                    <td><?php echo $imapData->soyouimapuser; ?></td>
                    <td><?php echo $imapData->soyouimaphost; ?></td>
                    <td><?php echo $imapData->soyouimapport; ?></td>
                    <td><?php echo $imapData->soyouimapssl; ?></td>
                    <td>
                        <?php
                        $chkConnection = $vmWareovhapp->app_imapconnection($imapData);
                        if ($chkConnection == 'Active') {
                            ?>
                            <span class="label active"><?php echo $chkConnection; ?></span> 
                        <?php } else { ?>
                            <span class="label closed" title="<?php echo $chkConnection; ?>"><?php echo 'Error'; ?></span>
                        <?php } ?>
                    </td>
                    <td><?php echo $imapData->language; ?></td>
                    <td>
                        <a href="<?php echo $applink; ?>&tab=<?php echo $_GET['tab']; ?>&addimap=true&editimap=<?php echo $imapData->id; ?>"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>
                        &nbsp; &nbsp;                     
                        <a href="<?php echo $applink; ?>&tab=<?php echo $_GET['tab']; ?>&deleteAccount=<?php echo $imapData->id; ?>" onclick="return confirm('Are you sure want to delete this imap setting?');"><img src="images/delete.gif" width="16" height="16" border="0" title="Delete"></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php
}
?>