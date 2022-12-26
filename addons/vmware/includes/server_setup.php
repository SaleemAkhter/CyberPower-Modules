<?php



use Illuminate\Database\Capsule\Manager as Capsule;



if (file_exists(__DIR__ . '/soap_class.php'))

    include_once __DIR__ . '/soap_class.php';



$context = stream_context_create(array(

    'ssl' => array(

        'verify_peer' => false,

        'verify_peer_name' => false,

        'allow_self_signed' => true

    )

));

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

                        <h3><?php echo $LANG['addss']; ?></h3>

                        <p>

                            <?php echo $LANG['vmserverdesc']; ?>&nbsp;

                        </p>

                    </div>

                    <?php

                    $connection = true;

                    $clientcon = true;

                    $create_server = filter_var($_POST['create_server'], FILTER_SANITIZE_STRING);

                    $server_name = filter_var($_POST['server_name'], FILTER_SANITIZE_STRING);

                    $vsphereip = filter_var($_POST['vsphereip'], FILTER_SANITIZE_STRING);

                    $vsphereusername = filter_var($_POST['vsphereusername'], FILTER_SANITIZE_STRING);

                    $vspherepassword = filter_var($_POST['vspherepassword'], FILTER_SANITIZE_STRING);

                    //                    $consoleusername = filter_var($_POST['consoleusername'], FILTER_SANITIZE_STRING);

                    //                    $consolepassword = filter_var($_POST['consolepassword'], FILTER_SANITIZE_STRING);

                    $esxi = filter_var($_POST['esxi'], FILTER_SANITIZE_STRING);

                    $edit_ss = filter_var($_GET['edit_ss'], FILTER_SANITIZE_STRING);

                    if (!empty($create_server)) {



                        if (!empty($server_name) && !empty($vsphereip) && !empty($vsphereusername) && !empty($vspherepassword)) {



                            Capsule::Schema()->table('mod_vmware_server', function ($table) {

                                if (!Capsule::Schema()->hasColumn('mod_vmware_server', 'esxi'))

                                    $table->tinyInteger('esxi');
                            });



                            if (isset($esxi) && !empty($esxi))

                                $esxi = 1;

                            else

                                $esxi = 0;



                            if (isset($_GET['edit_ss'])) {

                                if (substr($vspherepassword, 0, 6) == '******') {

                                    $getDbPw = Capsule::table('mod_vmware_server')->where('id', $edit_ss)->first();

                                    $encryptPw = $getDbPw->vspherepassword;

                                    $decryptPw = $vmWare->vmwarePwEncryptDcrypt($encryptPw);

                                    if ($decryptPw['result'] != 'success') {

                                        $msg = $decryptPw['message'];

                                        $clientcon = false;

                                        $connection = false;
                                    }

                                    $vspherepassword = $decryptPw['password'];
                                }

                                //                                if (substr($_POST['consolepassword'], 0, 6) == '******') {

                                //                                    $getDbPw = Capsule::table('mod_vmware_server')->where('id', $edit_ss)->first();

                                //                                    $encryptConsolePw = $getDbPw->consolepassword;

                                //                                    $decryptConsolePw = $vmWare->vmwarePwEncryptDcrypt($encryptConsolePw);

                                //                                    if ($decryptConsolePw['result'] != 'success') {

                                //                                        $msg = $decryptConsolePw['message'];

                                //                                        $clientcon = false;

                                //                                        $connection = false;

                                //                                    }

                                //                                    $consolepassword = $decryptConsolePw['password'];

                                //                                }

                            }

                            $encryptPw = $vmWare->vmwarePwEncryptDcrypt(html_entity_decode($vspherepassword), true);

                            if ($encryptPw['result'] != 'success') {

                                $msg = $encryptPw['message'];

                                $clientcon = false;

                                $connection = false;
                            }

                            //                            $encryptConsolePw = $vmWare->vmwarePwEncryptDcrypt(html_entity_decode($consolepassword), true);

                            //                            if ($encryptConsolePw['result'] != 'success') {

                            //                                $msg = $encryptConsolePw['message'];

                            //                                $clientcon = false;

                            //                                $connection = false;

                            //                            }

                            $data = [

                                'server_name' => $server_name,

                                'vsphereip' => $vsphereip,

                                'vsphereusername' => $vsphereusername,

                                'vspherepassword' => $encryptPw['password'],

                                'consoleusername' => '',

                                'consolepassword' => '',

                                'esxi' => $esxi

                            ];



                            try {

                                $client = new soapclientd($vsphereip . '/sdk/vimService.wsdl', array('location' => $vsphereip . '/sdk', 'trace' => 1, 'stream_context' => $context));
                            } catch (Exception $e) {

                                $msg = $e->getMessage();

                                $clientcon = false;

                                $connection = false;
                            }



                            if ($clientcon == true) {

                                try {

                                    $request = new stdClass();

                                    $request->_this = array('_' => 'ServiceInstance', 'type' => 'ServiceInstance');

                                    $response = $client->__soapCall('RetrieveServiceContent', array((array) $request));
                                } catch (Exception $e) {

                                    $msg = $e->getMessage();

                                    $connection = false;
                                }

                                $ret = $response->returnval;

                                try {

                                    $request = new stdClass();

                                    $request->_this = $ret->sessionManager;

                                    $request->userName = $vsphereusername;

                                    $request->password = html_entity_decode($vspherepassword);



                                    $response = $client->__soapCall('Login', array((array) $request));
                                } catch (Exception $e) {

                                    $msg = $e->getMessage();

                                    $connection = false;
                                }
                            }



                            if ($connection == true && $clientcon == true) {

                                if (isset($_GET['edit_ss'])) {

                                    $edit_ss = filter_var($_GET['edit_ss'], FILTER_SANITIZE_STRING);

                                    try {

                                        Capsule::table('mod_vmware_server')->where('id', $edit_ss)->update($data);

                                        $vars['upadte_success'] = 'success';
                                    } catch (Exception $ex) {

                                        $vars['upadte_error'] = $ex->getMessage();
                                    }



                                    header('location:' . $modulelink . '&action=server_setup&update=true');
                                } else {

                                    try {

                                        Capsule::table('mod_vmware_server')->insert($data);

                                        $vars['add_success'] = 'success';
                                    } catch (Exception $ex) {

                                        $vars['add_error'] = $ex->getMessage();
                                    }

                                    header('location:' . $modulelink . '&action=server_setup&add=true');
                                }
                            } else {

                                $_SESSION['connectionerror'] = $msg;

                                header('location:' . $modulelink . '&action=server_setup&error=connectionerror');

                                die();
                            }
                        } else {

                            if (!isset($_GET['edit_ss']))

                                $vars['custom_error'] = $LANG['allrequired'];

                            else

                                header('location:' . $modulelink . '&action=server_setup&custom_error=true');
                        }
                    }

                    if (isset($_GET['delete_ss']) && !empty($_GET['delete_ss'])) {

                        $delete_ss = filter_var($_GET['delete_ss'], FILTER_SANITIZE_STRING);

                        try {

                            Capsule::table('mod_vmware_server')->where('id', $delete_ss)->delete();

                            $vars['delete_success'] = 'success';
                        } catch (Exception $ex) {

                            $vars['delete_error'] = $ex->getMessage();
                        }

                        header('location:' . $modulelink . '&action=server_setup&delete=true');
                    }

                    $ssData = array();

                    ?>

                    <?php

                    if (isset($_GET['add']) && !empty($_GET['add']))

                        echo '<div class="successbox">' . $LANG['addedsucceessmsg'] . '</div>';

                    if (isset($_GET['update']) && !empty($_GET['update']))

                        echo '<div class="successbox">' . $LANG['updatesucceessmsg'] . '</div>';

                    if (isset($_GET['delete']) && !empty($_GET['delete']))

                        echo '<div class="successbox">' . $LANG['deletesucceessmsg'] . '</div>';

                    if (isset($vars['custom_error']) && !empty($vars['custom_error']))

                        echo '<div class="errorbox">' . $vars['custom_error'] . '</div>';

                    if (isset($_GET['custom_error']) && !empty($_GET['custom_error']))

                        echo '<div class="errorbox">' . $LANG['allrequired'] . '</div>';

                    if (isset($_GET['error']) && $_GET['error'] == 'connectionerror' && isset($_SESSION['connectionerror'])) {

                        echo '<div class="errorbox">' . $_SESSION['connectionerror'] . '</div>';

                        unset($_SESSION['connectionerror']);
                    }

                    ?>

                    <?php if (!isset($_GET['edit_ss'])) { ?>

                        <div class="btn_section">

                            <button onclick="jQuery('#ss_form').fadeToggle(1000);"><?php echo $LANG['addss']; ?></button>

                        </div>

                    <?php

                    } else {

                        $edit_ss = filter_var($_GET['edit_ss'], FILTER_SANITIZE_STRING);

                        $ssData = Capsule::table('mod_vmware_server')->where('id', $edit_ss)->get();

                        if (count($ssData) > 0)
                            $ssData = (array) $ssData[0];
                        else
                            $ssData = [];
                    }
                    ?>

                    <form action="" method="post" id="ss_form" style="display:<?php if (!isset($_GET['edit_ss'])) { ?>none<?php } ?>;">

                        <table class="form partner-form" width="100%" border="0" cellspacing="2" cellpadding="3">

                            <tbody>

                                <tr>

                                    <td width="15%" class="fieldlabel">

                                        <?php echo $LANG['server']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="text" size="30" name="server_name" id="server_name" value="<?php

                                                                                                                if (!empty($ssData)) {

                                                                                                                    echo $ssData['server_name'];
                                                                                                                }

                                                                                                                ?>" required="">

                                    </td>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['vsphereip']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="text" size="30" name="vsphereip" id="vsphereip" title="Enter with http:// or https://" placeholder="http://0.0.0.0" value="<?php

                                                                                                                                                                                if (!empty($ssData)) {

                                                                                                                                                                                    echo $ssData['vsphereip'];
                                                                                                                                                                                }

                                                                                                                                                                                ?>" required="">

                                        <br>

                                        <span><?php echo $LANG['vsphereipdesc']; ?></span>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['vsphereusername']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="text" size="30" name="vsphereusername" id="vsphereusername" value="<?php

                                                                                                                        if (!empty($ssData)) {

                                                                                                                            echo $ssData['vsphereusername'];
                                                                                                                        }

                                                                                                                        ?>" required="">

                                        <br>

                                        <span><?php echo $LANG['vsphereusernamedesc']; ?></span>

                                    </td>

                                    <td class="fieldlabel">

                                        <?php echo $LANG['vspherepassword']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="password" name="vspherepassword" id="vspherepassword" value="<?php

                                                                                                                    if (!empty($ssData)) {

                                                                                                                        echo '******';
                                                                                                                    }

                                                                                                                    ?>" required="">

                                        <br>

                                        <span><?php echo $LANG['vspherepassworddesc']; ?></span>

                                    </td>



                                </tr>

                                <!--                                <tr>

                                    <td class="fieldlabel" width="15%">

                                        <?php echo $LANG['consoleusername']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="text" size="30" name="consoleusername" id="consoleusername" value="<?php

                                                                                                                        if (!empty($ssData)) {

                                                                                                                            echo $ssData['consoleusername'];
                                                                                                                        }

                                                                                                                        ?>" required="">

                                        <br>

                                        <span><?php echo $LANG['consoleusernamedesc']; ?></span>&nbsp;<a target="_blank" href="https://whmcsglobalservices.com/members/knowledgebase.php?action=displayarticle&id=33"><?php echo $LANG['vmhowtocreatevmrcuser']; ?></a>

                                    </td>

                                    <td class="fieldlabel">

                                        <?php echo $LANG['consolepassword']; ?>

                                    </td>

                                    <td class="fieldarea">

                                        <input type="password" name="consolepassword" id="consolepassword" value="<?php

                                                                                                                    if (!empty($ssData)) {

                                                                                                                        echo '******';
                                                                                                                    }

                                                                                                                    ?>" required="">

                                        <br>

                                        <span><?php echo $LANG['consolepassworddesc']; ?></span>

                                    </td>



                                </tr>-->

                                <?php //if (!empty($ssData)) {
                                ?>

                                <tr>

                                    <td width="20%" class="fieldlabel">

                                        <?php echo $LANG['esxienable']; ?>

                                    </td>

                                    <td width="80%" class="fieldarea">

                                        <input type="checkbox" name="esxi" <?php

                                                                            if (!empty($ssData) && $ssData['esxi'] == '1') {

                                                                                echo 'checked="checked"';
                                                                            }

                                                                            ?> value="1">&nbsp;&nbsp;

                                        <span><?php echo $LANG['esxienabledesc']; ?></span>

                                    </td>





                                </tr>

                                <?php //}
                                ?>

                                <tr>

                                    <td colspan="100%">

                                        <div class="form_btn">

                                            <input type="submit" name="create_server" value="<?php echo $LANG['submit']; ?>">

                                            &nbsp;&nbsp;

                                            <?php

                                            if (isset($_GET['edit_ss'])) {

                                            ?>

                                                <a href="<?php echo $modulelink; ?>&action=server_setup"><input type="button" name="cancel" value="<?php echo $LANG['cancel']; ?>"></a>

                                            <?php

                                            } else {

                                            ?>

                                                <input type="reset" onclick="jQuery('#ss_form').fadeOut(1000);" value="<?php echo $LANG['cancel']; ?>">

                                            <?php

                                            }

                                            ?>

                                        </div>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </form>

                    <?php if (!isset($_GET['edit_ss'])) { ?>

                        <div class="tablebg">

                            <table id="sortabletbl0" class="datatable partnerdetail_table" width="100%" border="0" cellspacing="1" cellpadding="3">

                                <tbody>

                                    <tr>

                                        <th><?php echo $LANG['server']; ?></th>

                                        <th><?php echo $LANG['vsphereip']; ?></th>

                                        <th><?php echo $LANG['vsphereusername']; ?></th>

                                        <!--                                        <th><?php echo $LANG['consoleusername']; ?></th>-->

                                        <!--<th><?php echo $LANG['vspherepassword']; ?></th>-->

                                        <th><?php echo $LANG['status']; ?></th>

                                        <th><?php echo $LANG['action']; ?></th>

                                    </tr>

                                    <?php

                                    $ssQuery = Capsule::table('mod_vmware_server')->get();



                                    foreach ($ssQuery as $ssResult01) {

                                        $ssQuery = (array) $ssResult01;

                                        $vspherepassword = '';

                                        $vsphereip = explode('://', $ssQuery['vsphereip']);

                                        for ($i = 1; $i <= strlen($ssQuery['vspherepassword']); $i++) {

                                            $vspherepassword .= '*';
                                        }



                                        $clientcon = true;

                                        $connection = true;

                                        $decryptPw = $vmWare->vmwarePwEncryptDcrypt($ssQuery['vspherepassword']);

                                    ?>

                                        <tr>

                                            <td><?php echo $ssQuery['server_name']; ?></td>

                                            <!--<td><?php echo $esxipassword; ?></td>-->

                                            <td><?php echo $vsphereip[1]; ?></td>

                                            <td><?php echo $ssQuery['vsphereusername']; ?></td>

                                            <!--                                            <td><?php echo $ssQuery['consoleusername']; ?></td>-->

                                            <!--<td><?php echo $vspherepassword; ?></td>-->

                                            <td id="status_<?php echo $ssQuery['id']; ?>">

                                                <script>
                                                    getServerStatus(this, '<?php echo $ssQuery['id']; ?>', '<?php echo $modulelink; ?>');
                                                </script>

                                            </td>

                                            <td>

                                                <a href="<?php echo $modulelink; ?>&action=server_setup&edit_ss=<?php echo $ssQuery['id']; ?>">

                                                    <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>

                                                &nbsp;

                                                <a href="<?php echo $modulelink; ?>&action=server_setup&delete_ss=<?php echo $ssQuery['id']; ?>" onclick="return confirm('Are you sure delete this row ?');">

                                                    <img src="images/delete.gif" width="16" height="16" border="0" alt="Cancel &amp; Delete">

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    <?php } ?>

                </div>

                <?php

                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))

                    require_once dirname(__DIR__) . '/includes/footer.php';

                ?>

            </div>





        </div>

    </div>

</div>