<?php

use Illuminate\Database\Capsule\Manager as Capsule;
?>
<script>
    $(document).on('change', 'input[name="mailsettigs"]:radio', function () {
        var selectedradio = $(this).val();
        if (selectedradio == 'gmail') {
            $('#imapform').css('display', 'none');
            $('#gmaildiv').css('display', 'block');
        } else {
            $('#imapform').css('display', 'block');
            $('#gmaildiv').css('display', 'none');
        }
    });
</script>
<style>
    table.form td.fieldarea {
        width: 85%;
    }
</style>
<div class="back_page">
    <a href="<?php echo $applink; ?>&tab=imap_setting"><?php echo $LANG['back']; ?></a>
</div>
<form action="<?php echo $applink; ?>&tab=key_setup" method="post" id="" style="">
    <div class="radio_buttons">
        <label><input type="radio" name="mailsettigs" value="webmail" checked=""> <?php echo $LANG['webmail']; ?></label>&nbsp;<label><input type="radio" name="mailsettigs" value="gmail"> <?php echo $LANG['gmail']; ?></label>
    </div>
    </br>
</form>	
<form  action="<?php echo $applink ?>&tab=mailsetting" method="post" name="soyoustartform" id="imapform" >
    <input type="hidden" name="action" value="imap"/>
    <table style="width:100%" cellspacing="10" cellpadding="10" class="form partner-form">
        <tr>
            <td></td>
            <td></td>
        </tr>	
        <tr>
        <script>
            $(document).ready(function () {
                $('#userAccount option').each(function () {
                    var value = $(this).attr('value');
                    $(this).text(value);
                });
            });

        </script>
        <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['accountuser']; ?></td>
        <td class="fieldarea">
            <select name="userAccount" id="userAccount">                             
                <?php
                if (empty($account_user)) {
                    $accountQuery = Capsule::select("SELECT account_number FROM mod_ovh_manage_apps where account_number not in (select account_user from mod_ovh_imap where gmailaddr != '') group by account_number ");
                    foreach ($accountQuery as $accountData) {
                        ?>
                        <option <?php
                        if ($account_user == $accountData->account_number) {
                            echo ' selected="selected" ';
                        }
                        ?> value="<?php echo $accountData->account_number; ?>"></option>
                        <?php } ?>
                    <?php } else { ?>   
                    <option value="<?php echo $account_user; ?>"><?php echo $account_user; ?></option>
                <?php } ?> 
            </select>
        </td>
        </tr>

        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['incomingmailservername']; ?></td>
            <td class="fieldarea"><input type="text" required="" name="hostname" placeholder="mymailserver.com" id="hostname" value="<?php echo $hostname; ?>"/></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['portnumber']; ?></td>
            <td class="fieldarea">
                <input type="text" name="portnumber" id="portnumber" required="" placeholder="143" value="<?php echo $portnumber; ?>" />
            </td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['ssltype']; ?></td>
            <td class="fieldarea">
                <select required="" name="ssltype" id="ssltype">
                    <option value="default">Default</option>
                    <option <?php
                    if ($soyouimapssl == 'tls') {
                        echo ' selected="selected" ';
                    }
                    ?> value="tls">TLS</option>
                    <option <?php
                    if ($soyouimapssl == 'ssl') {
                        echo ' selected="selected" ';
                    }
                    ?> value="ssl">SSL</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['username']; ?></td>
            <td class="fieldarea"><input type="text" required="" name="username" id="username" value="<?php echo $username; ?>"/></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['password']; ?></td>
            <td class="fieldarea"><input type="password" required="" name="password" id="password" value="<?php
                if (!empty($password)) {
                    echo 'xxxxxxxx';
                };
                ?>" /></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['language']; ?></td>
            <td  class="fieldarea">
                <select name="language" id="language" required="">
                    <option <?php
                    if ($language == 'english') {
                        echo ' selected="selected" ';
                    }
                    ?> value="english">English</option>
                    <option <?php
                    if ($language == 'french') {
                        echo ' selected="selected" ';
                    }
                    ?> value="french">French</option>
                    <option <?php
                    if ($language == 'spanish') {
                        echo ' selected="selected" ';
                    }
                    ?> value="spanish">Spanish</option>
                    <option <?php
                    if ($language == 'portuguese') {
                        echo ' selected="selected" ';
                    }
                    ?> value="portuguese">Portuguese</option>
                    <option <?php
                    if ($language == 'italian') {
                        echo ' selected="selected" ';
                    }
                    ?> value="italian">Italian</option>
                    <option <?php
                    if ($language == 'german') {
                        echo ' selected="selected" ';
                    }
                    ?> value="german">German</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" id="imapsubmit" name="submit" class="ado_vew ado_vew2" value="submit"/>  <input type="button" id="testid" name="test" value="<?php echo $LANG['testconnection']; ?>" class="ado_vew ado_vew2"/></td>
        </tr>

    </table>
</form>
<div id='gmaildiv' style="<?php
if ($type == 'gmail') {
    echo'display:block';
} else {
    echo'display:none';
}
?>">
    <div class='successbox' id="successdiv" style="display:none"><?php echo $LANG['gmail']; ?></div>
    <?php
    if (isset($_GET['code'])) {
        echo "<div class='successbox'>" . $LANG['congmail'] . "</div>";
    }
    ?>
    <form method="post"  id="gmailSettingform" action="">
        <table style="width:100%;" cellspacing="1" cellpadding="3" class="form partner-form">
            <thead>
                <tr>
                    <th colspan="2" align="center"><a href="https://console.developers.google.com/" target="_blank"><?php echo $LANG['imaphelplink']; ?></a></th>
                </tr>
            </thead><tbody>

                <tr>
                    <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['accountuser']; ?></td>
                    <td class="fieldarea">
                        <script>
                            $(document).ready(function () {
                                $('#uAccount option').each(function () {
                                    var value = $(this).attr('value');
                                    $(this).text(value);
                                });
                            });
                        </script>
                        <select name="uAccount" id="uAccount" style="width:180px;">
                            <?php if (empty($account_user)) { ?>
                                <?php
                                $accountQuery = Capsule::select("SELECT account_number FROM mod_ovh_manage_apps where account_number not in (select account_user from mod_ovh_imap where soyouimapuser != '') group by account_number ");
                                foreach ($accountQuery as $accountData) {
                                    ?>
                                    <option value="<?php echo trim($accountData->account_number); ?>"><?php echo trim($accountData->account_number); ?></option>
                                <?php } ?>		
                            <?php } else { ?>   
                                <option value="<?php echo $account_user; ?>"><?php echo $account_user; ?></option>
                            <?php } ?>   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['appclientid']; ?></td><td class="fieldarea"><input type="text" value="<?php echo $gclientId; ?>" name="clientid" id="gclientid"></td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['appsecretkey']; ?></td><td class="fieldarea"><input type="text" value="<?php echo $gsecretkey; ?>" name="clientid" id="gclientSecret"></td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['email']; ?></td><td class="fieldarea"><input type="text" value="<?php echo $gmailaddr; ?>" name="clientid" id="gmailaddr"></td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['language']; ?></td>
                    <td class="fieldarea">
                        <select name="language" id="gclanguage">
                            <option <?php
                            if ($language == 'english') {
                                echo ' selected="selected" ';
                            }
                            ?> value="english">English</option>
                            <option <?php
                            if ($language == 'french') {
                                echo ' selected="selected" ';
                            }
                            ?> value="french">French</option>
                            <option <?php
                            if ($language == 'spanish') {
                                echo ' selected="selected" ';
                            }
                            ?> value="spanish">Spanish</option>
                            <option <?php
                            if ($language == 'portuguese') {
                                echo ' selected="selected" ';
                            }
                            ?> value="portuguese">Portuguese</option>
                            <option <?php
                            if ($language == 'italian') {
                                echo ' selected="selected" ';
                            }
                            ?> value="italian">Italian</option>
                            <option <?php
                            if ($language == 'german') {
                                echo ' selected="selected" ';
                            }
                            ?> value="german">German</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="button" id="saveGsettings" value="Save" class="ado_vew ado_vew2"></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
