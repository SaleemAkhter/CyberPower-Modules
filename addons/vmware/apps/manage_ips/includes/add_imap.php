<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$imapData = [];
$editImapId = '';
if (isset($_GET['editimap']) && !empty($_GET['editimap'])) {
    $editImapId = $whmcs->get_req_var('editimap');
    $imapData = Capsule::table('mod_ovh_imap')->where('id', $editImapId)->first();
    if (count($imapData) > 0)
        $imapData = $imapData;
    else
        $imapData = [];
}
?>
<script>
    $(document).on('change', 'input[name="mailsettigs"]:radio', function() {
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
<form action="<?php echo $applink; ?>&tab=key_setup&appoutput=true&addimap=true" method="post" id="" style="">
    <div class="radio_buttons">
        <label><input type="radio" name="mailsettigs" value="webmail" checked=""> <?php echo $LANG['webmail']; ?></label>
    </div>
    </br>
</form>
<form action="<?php echo $applink ?>&tab=imap_setting" method="post" name="soyoustartform" id="imapform">
    <?php
    if (!empty($imapData))
        echo '<input type="hidden" name="imapid" value="' . $editImapId . '"/>';
    ?>
    <input type="hidden" name="action_perform" value="imap_config" />
    <div id="ajaxsts"></div>
    <table style="width:100%" cellspacing="10" cellpadding="10" class="form partner-form">
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <script>
                $(document).ready(function() {
                    $('#userAccount option').each(function() {
                        var value = $(this).attr('value');
                        $(this).text(value);
                    });
                });
            </script>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['accountuser']; ?></td>
            <td class="fieldarea">
                <select name="userAccount" id="userAccount" required="required">
                    <?php
                    if (empty($imapData->account_user)) {
                        $accountQuery = Capsule::select("SELECT account_number FROM mod_ovh_manage_apps where account_number not in (select account_user from mod_ovh_imap) group by account_number");
                        foreach ($accountQuery as $accountData) {
                    ?>
                            <option value="<?php echo $accountData->account_number; ?>"><?php echo trim($accountData->account_number); ?></option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="<?php echo $imapData->account_user; ?>" selected="selected"><?php echo $imapData->account_user; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['incomingmailservername']; ?></td>
            <td class="fieldarea"><input type="text" required="" name="hostname" placeholder="mymailserver.com" id="hostname" value="<?php echo (!empty($imapData->soyouimaphost)) ? $imapData->soyouimaphost : ''; ?>" required="required" />
                &nbsp;&nbsp;
                <input type="button" onclick="testConnection(this);" class="testconnection" value="<?php echo $LANG['testconnection']; ?>"></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['portnumber']; ?></td>
            <td class="fieldarea">
                <input type="text" name="portnumber" id="portnumber" required="" placeholder="143" value="<?php echo (!empty($imapData->soyouimapport)) ? $imapData->soyouimapport : ''; ?>" required="required" />
            </td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['ssltype']; ?></td>
            <td class="fieldarea">
                <select required="" name="ssltype" id="ssltype">
                    <option value="default">Default</option>
                    <option <?php
                            $soyouimapssl = (!empty($imapData->soyouimapssl)) ? $imapData->soyouimapssl : '';
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
            <td class="fieldarea"><input type="text" required="" name="username" id="username" value="<?php echo (!empty($imapData->soyouimapuser)) ? $imapData->soyouimapuser : ''; ?>" required="required" /></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['password']; ?></td>
            <td class="fieldarea"><input type="password" required="" name="password" id="password" value="<?php
                                                                                                            if (!empty($imapData->soyouimappass)) {
                                                                                                                echo '******';
                                                                                                            };
                                                                                                            ?>" required="required" /></td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel" style="text-align: left;"><?php echo $LANG['language']; ?></td>
            <td class="fieldarea">
                <select name="language" id="language" required="">
                    <option <?php
                            $language = (!empty($imapData->language)) ? $imapData->language : '';
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
            <td colspan="100%">
                <div class="form_btn">
                    <input type="submit" name="submit" value="<?php echo $LANG['submit']; ?>">
                    &nbsp;&nbsp;
                    <input type="reset" value="<?php echo $LANG['cancel']; ?>">
                </div>
            </td>
        </tr>

    </table>
</form>