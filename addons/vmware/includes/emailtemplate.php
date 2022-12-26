<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

if ($message != "") {

    $encode_mesage = $message;

    $templateID = filter_var($_POST['templateId'], FILTER_SANITIZE_STRING);

    $subject = filter_var($_POST['tempsubject'], FILTER_SANITIZE_STRING);

    Capsule::table('tblemailtemplates')->where('id', $templateID)->update(['subject' => $subject, 'message' => $encode_mesage]);

    echo '<div class="alert alert-success">' . $LANG['templateupdated'] . '</div>';
}

$operation = filter_var($_GET['operation'], FILTER_SANITIZE_STRING);

$templateIds = filter_var($_GET['templateIds'], FILTER_SANITIZE_STRING);

$templateId = filter_var($_GET['templateId'], FILTER_SANITIZE_STRING);

if ($operation == "disablebulk" && $templateIds != "") {

    $templateIDs = trim($templateIds, ",");

    $templateids = explode(",", $templateIDs);

    foreach ($templateids as $key => $value) {

        Capsule::table('tblemailtemplates')->where('id', $value)->update(['disabled' => '1']);
    }

    echo '<div class="alert alert-success">' . $LANG['templatedisabled'] . '</div>';
}



if ($operation == "enablebulk" && $templateIds != "") {

    $templateIDs = trim($templateIds, ",");

    $templateids = explode(",", $templateIDs);

    foreach ($templateids as $key => $value) {

        Capsule::table('tblemailtemplates')->where('id', $value)->update(['disabled' => '0']);
    }

    echo '<div class="alert alert-success">' . $LANG['templateenabled'] . '</div>';
}





if ($operation == "disabletemplate" && $templateId != "") {

    $templateid = $templateId;

    Capsule::table('tblemailtemplates')->where('id', $templateid)->update(['disabled' => '1']);

    echo '<div class="alert alert-success">' . $LANG['templatedisabled'] . '</div>';
}

if ($operation == "enabletemplate" && $templateId != "") {

    $templateid = $templateId;

    Capsule::table('tblemailtemplates')->where('id', $templateid)->update(['disabled' => '0']);

    echo '<div class="alert alert-success">' . $LANG['templateenabled'] . '</div>';
}
?>

<script type="text/javascript" src="../assets/js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
    function home_settings() {

        window.location.href = '<?php echo $modulelink . "&tab=mail_template_settings"; ?>';

    }

    function disableSelected() {

        //alert('yse');

        var templatearray = '';

        $('.checkall:checked').each(function () {

            //console.log(this.value);

            templatearray += this.value + ",";

        });

        //alert(templatearray);

        window.location.href = document.URL + "&operation=disablebulk&templateIds=" + templatearray;

    }



    function enableSelected() {

        //alert('yse');

        var templatearray = '';

        $('.checkall:checked').each(function () {

            //console.log(this.value);

            templatearray += this.value + ",";

        });

        //alert(templatearray);

        window.location.href = document.URL + "&operation=enablebulk&templateIds=" + templatearray;

    }









    var editorEnabled = true;



    function toggleEditor() {

        if (editorEnabled == true) {

            tinymce.EditorManager.execCommand("mceRemoveControl", true, "email_msg");

            editorEnabled = false;

        } else {

            tinymce.EditorManager.execCommand("mceAddControl", true, "email_msg");

            editorEnabled = true;

        }

    }



    function insertMergeField(mfield) {

        $("#email_msg").tinymce().execCommand("mceInsertContent", false, '{$' + mfield + '}');

    }



    function editTemplate(id) {

        window.location.href = document.URL + "&operation=edittemplate&templateId=" + id;

    }

    function enableTemplate(id) {

        window.location.href = document.URL + "&operation=enabletemplate&templateId=" + id;

    }

    function disableTemplate(id) {

        window.location.href = document.URL + "&operation=disabletemplate&templateId=" + id;

        //        jQuery.ajax({

        //			url:document.URL,

        //			method:'POST',

        //			data:'action=disabletemplate&templateId='+id,

        //			success:function(data){

        //                            //alert(data);

        //                            if(data=='success'){

        //                               

        //                            }

        //				//jQuery("#responseid").html(data);

        //				//jQuery("#imapsubmit").val('Submit');	

        //			}

        //		});

    }

    $(document).ready(function () {



        $("textarea.tinymce").tinymce({

            // Location of TinyMCE script

            script_url: "../assets/js/tiny_mce/tiny_mce.js",

            // General options

            theme: "advanced",

            plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",

            entity_encoding: "raw",

            // Theme options

            theme_advanced_buttons1: "fontselect,fontsizeselect,forecolor,backcolor,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code",

            theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",

            theme_advanced_toolbar_location: "top",

            theme_advanced_toolbar_align: "left",

            theme_advanced_statusbar_location: "bottom",

            theme_advanced_resizing: true,

            convert_urls: false,

            relative_urls: false,

            forced_root_block: false

        });





        $("#checkall0").click(function () {

            // alert("ok");

            if ($('#checkall0').prop("checked") == false) {

                //$("#displayRec .checkall").removeAttr("checked");

                $("#displayRec .checkall").prop('checked', false);

            } else {

                //$("#displayRec .checkall").attr("checked", this.checked);

                $("#displayRec .checkall").prop('checked', true);

            }

        });

    });
</script>

<?php
if (file_exists(dirname(__DIR__) . '/includes/header.php'))
    require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="vmware_heading">

    <h3><?php echo $LANG['mailtemplatesetting']; ?></h3>

    <p>

        <?php echo $LANG['managetemplates']; ?>&nbsp;

    </p>

</div>

<!--<div class="alert alert-info"><?php echo $LANG['managetemplates']; ?></div>-->

<div class="tab0box" id="tab0box">

    <div id="tab_content">

        <table style="width:100%;margin: 10px" id="displayRec" cellspacing="1" cellpadding="3" class="datatable">

            <thead>

                <tr>

                    <th width="20"><input type="checkbox" id="checkall0"></th>

                    <th><?php echo $LANG['emailtemplate']; ?></th>

                    <th><?php echo $LANG['status']; ?></th>

                    <th><?php echo $LANG['disable']; ?></th>

                    <th><?php echo $LANG['actions']; ?></th>

                </tr>

            </thead>

            <tbody>

                <?php
                $getCustommailTemplate = Capsule::select("SELECT * FROM tblemailtemplates WHERE type IN ('product','general','admin') AND custom='1' AND name IN('VMware Welcome Email','VMware Bandwidth Usage Notification Email','VMware Service Suspension Notification Email','Server Reinstall Notification Email','VMware Migration Request Notification Email','VMware Migration Notification Email')");

                if (count($getCustommailTemplate) > 0 && !empty($getCustommailTemplate)) {

                    foreach ($getCustommailTemplate as $row) {
                        ?>

                        <tr>

                            <td><input type="checkbox" name="selectedorders[]" value="<?php echo $row->id; ?>" class="checkall"></td>

                            <td align="center"><?php echo $row->name; ?></td>

                            <td align="center"><?php
                                if ($row->disabled == "0") {

                                    echo 'Enabled';
                                } else {

                                    echo 'Disabled';
                                }
                                ?></td>

                            <td align="center">

                                <?php if ($row->disabled == "0") { ?>

                                    <img src="<?php echo $imgPath; ?>disable.png" title="Disable" onclick="disableTemplate('<?php echo $row->id; ?>');" class="cursor">

                                <?php } else { ?>

                                    <img src="<?php echo $imgPath; ?>enable.png" title="Enable" onclick="enableTemplate('<?php echo $row->id; ?>');" class="cursor">

                                <?php } ?>

                            </td>

                            <td align="center"><img src="<?php echo $imgPath; ?>edit.png" title="Edit" onclick="editTemplate('<?php echo $row->id; ?>');" class="cursor">

                            </td>

                        </tr>





                        <?php
                    }
                } else {

                    echo '<tr><td colspan="5">' . $LANG['notemplate'] . '</td></tr>';
                }
                ?>

            </tbody>

        </table>



        <p> <?php echo $LANG['disableddelctemp']; ?>: <input type="button" name="disable_all" class="btn btn-danger" onclick="disableSelected();" value="Disable">

            <input type="button" name="disable_all" class="btn btn-success" onclick="enableSelected();" value="Enable">

        </p>



        <?php
        if ($operation == "edittemplate" && $templateId != "") {

            $get_template_data = Capsule::table('tblemailtemplates')->where('id', $templateId)->get();

            if (count($get_template_data) > 0 && !empty($get_template_data)) {

                $fetchdata = (array) $get_template_data[0];
                ?>

                <p align="center" style="margin-top: 15px"><b><?php echo $LANG['templatedesired']; ?> "<?php echo $fetchdata['name']; ?>"</b></p>

                <form method="POST" action="">

                    <input type="hidden" name="templateId" value="<?php echo $fetchdata['id']; ?>">

                    <label><?php echo $LANG['subject']; ?> :</label><input type="text" name="tempsubject" id="tempsubject" size="80" style="margin-left: 5px" value="<?php echo $fetchdata['subject']; ?>"><br><br>

                    <textarea rows="7" style="width:100%" name="message" id="email_msg" class="tinymce"><?php echo strip_tags($fetchdata['message']); ?></textarea>

                    <p align="center" style="margin-top: 10px"><input type="submit" name="submit" value="Save" class="btn btn-primary"> <input type="button" class="btn btn-info" name="back" onclick="home_settings();" value="Back"></p>

                </form>
                <!-- 
                                <p><b><?php echo $LANG['availabletemplate']; ?></b></p>
                
                                               <div style="border:1px solid #8FBCE9;background:#ffffff;color:#000000;padding:5px;height:150px;overflow:auto;font-size:13px;z-index:10;">
                                        
                <?php
                if ($fetchdata['name'] == 'Server Re-installed') {

                    echo '{$clientname}<br>{$ip_address}<br>{$custom_server_name}<br>{$operating_system}<br>{$custom_user_account}<br>{$new_password}';
                } elseif ($fetchdata['name'] == 'Hardware Reboot') {

                    echo '{$clientname}<br>{$custom_server_name}<br>{$operating_system}';
                } elseif ($fetchdata['name'] == 'Ftp Backup Password change') {

                    echo '{$clientname}<br>{$ftp_server_name}<br>{$new_password}';
                } elseif ($fetchdata['name'] == 'Server Details') {

                    echo '{$server_ipaddress}<br>{$server_username}<br>{$server_password}';
                } elseif ($fetchdata['name'] == 'Rescue-Pro Mode') {

                    echo '{$clientname}<br>{$ipaddress}<br>{$username}<br>{$password}';
                } elseif ($fetchdata['name'] == 'BSD10 Rescue mode') {

                    echo '{$clientname}<br>{$username}<br>{$password}';
                } elseif ($fetchdata['name'] == 'Service Monitoring') {

                    echo '{$clientname}<br>{$details}';
                } elseif ($fetchdata['name'] == 'Detection of an attack on IP') {

                    echo '{$ipaddress}';
                } elseif ($fetchdata['name'] == 'Spam Detected') {

                    echo '{$ipaddress}<br>{$detected_detail}';
                } else {  // for FTP Backup Configured
                    echo '{$clientname}<br>{$custom_server_name}<br>{$new_password}<br>{$ftp_server_name}';
                }
                ?>
                                        
                                                        </div>-->

                <?php
            }
        }
        ?>

    </div>

</div>

<?php
if (file_exists(dirname(__DIR__) . '/includes/footer.php'))
    require_once dirname(__DIR__) . '/includes/footer.php';
