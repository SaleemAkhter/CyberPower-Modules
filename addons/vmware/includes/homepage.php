<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$serverData = Capsule::table('mod_vmware_server')->get();

$cloneFilePermission = substr(sprintf('%o', fileperms(dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.clone_ticket.cache')), -3);

$wsdlFilePermission = substr(sprintf('%o', fileperms(dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.wsdl_class_map.cache')), -3);

$soapEnabled = class_exists("SOAPClient");

$ssh2 = extension_loaded('ssh2');
?>

<div id="wrapper">

    <div class="addon_container">

        <?php
        if (isset($_GET['success']) && $_GET['success'] == 'true') {

            echo '<div class="successbox"><strong><span class="title">Changes Saved Successfully!</span></strong><br>Your changes have been successfully updated.</div>';
        }
        ?>

        <a href="<?php echo $modulelink; ?>&action=modules_update"><button><?php echo $LANG['vmware_module_update']; ?></button></a>

        <div class="ad_content_area">

            <?php
            if (file_exists(dirname(__DIR__) . '/includes/header.php'))
                require_once dirname(__DIR__) . '/includes/header.php';
            ?>

            <div class="addon_inner">

                <h2 class="ad_title"><?php echo $LANG['about']; ?></h2>



                <div class="ad_content_sec">

                    <div class="add_version_sec">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ad_on_table">

                            <tr>

                                <td><?php echo $LANG['version']; ?></td>

                                <td align="right">V<?php echo $vars['version']; ?></td>

                            </tr>


                            <tr>

                                <td><?php echo $LANG['license']; ?></td>

                                <td align="right"><?php echo $vars['license_key']; ?></td>

                            </tr>

                            <tr bgcolor="f3f8fd">

                                <td><?php echo $LANG['licensestatus']; ?></td>

                                <td align="right"><span class="license active">Active</span></td>

                            </tr>



                            <tr>

                                <td><?php echo $LANG['author']; ?></td>

                                <td align="right">Whmcs Global Services</td>

                            </tr>

                            <tr bgcolor="f3f8fd">

                                <td><?php echo $LANG['productname']; ?></td>

                                <td align="right">Open Source Version</td>

                            </tr>

                            <tr>

                                <td><?php echo $LANG['lastupdated']; ?></td>

                                <td align="right">19 May, 2021</td>

                            </tr>

                        </table>

                    </div>

                    <div class="ad_review_sec">

                        <h2 align="center"><?php echo $LANG['module_requirement']; ?></h2>

                        <div class="health_box">

                            <?php
                            $memory_limit = ini_get('memory_limit');
                            ?>

                            <div class="memory_limit <?php if(substr($memory_limit, -1) == "G"){'meets';}else{ echo substr($memory_limit, 0, -1) < 512 ? 'doesnotmeet' : 'meets'; }?>">

                                <div class="panel">

                                    <div class="panel-heading"><?php echo $LANG['memory_limit']; ?></div>

                                    <div class="panel-body">
										
                                        <?php if(substr($memory_limit, -1) == "G"){
											?>
											
											<i class="fa fa-check" aria-hidden="true"></i> <?php echo str_replace('%s', $memory_limit, $LANG['memory_limit_meets']); ?>
											
											<?php
											
										}else{
											if (substr($memory_limit, 0, -1) >= 512) { ?>

                                            <i class="fa fa-check" aria-hidden="true"></i> <?php echo str_replace('%s', $memory_limit, $LANG['memory_limit_meets']); ?>

                                        <?php } else { ?>

                                            <i class="fa fa-times" aria-hidden="true"></i> <?php echo str_replace('%s', $memory_limit, $LANG['memory_limit_doesotmeets']); ?>

                                        <?php } 
										}?>

                                    </div>

                                </div>

                            </div>

                            <div class="permission <?php echo $cloneFilePermission < 777 ? 'doesnotmeet' : 'meets'; ?>">

                                <div class="panel">

                                    <div class="panel-heading"><?php echo $LANG['permission_check']; ?></div>

                                    <div class="panel-body">

                                        <?php if ($cloneFilePermission < 777) { ?>

                                            <i class="fa fa-times" aria-hidden="true"></i> <?php echo str_replace('%s', dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.clone_ticket.cache', $LANG['permission_check_meets']); ?>

                                        <?php } else { ?>

                                            <i class="fa fa-check" aria-hidden="true"></i> <?php echo str_replace('%s', dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.clone_ticket.cache', $LANG['permission_check_doesnot_meets']); ?>

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>

                            <div class="permission <?php echo $wsdlFilePermission < 777 ? 'doesnotmeet' : 'meets'; ?>">

                                <div class="panel">

                                    <div class="panel-heading"><?php echo $LANG['permission_check']; ?></div>

                                    <div class="panel-body">

                                        <?php if ($wsdlFilePermission < 777) { ?>

                                            <i class="fa fa-times" aria-hidden="true"></i> <?php echo str_replace('%s', dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.wsdl_class_map.cache', $LANG['permission_check_meets']); ?>

                                        <?php } else { ?>

                                            <i class="fa fa-check" aria-hidden="true"></i> <?php echo str_replace('%s', dirname(dirname(dirname(__DIR__))) . '/servers/vmware/vmwarephp/library/Vmwarephp/.wsdl_class_map.cache', $LANG['permission_check_doesnot_meets']); ?>

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>

                            <div class="permission <?php echo $soapEnabled ? 'meets' : 'doesnotmeet'; ?>">

                                <div class="panel">

                                    <div class="panel-heading"><?php echo $LANG['soap_extension']; ?></div>

                                    <div class="panel-body">

                                        <?php if ($soapEnabled) { ?>

                                            <i class="fa fa-check" aria-hidden="true"></i> <?php echo $LANG['soap_extension_meets']; ?>

                                        <?php } else { ?>

                                            <i class="fa fa-times" aria-hidden="true"></i> <?php echo $LANG['soap_extension_doesnot_meets']; ?>

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>

                            <!--                            <div class="permission <?php // echo $ssh2 ? 'meets' : 'doesnotmeet';
                                                                                    ?>">

                                <div class="panel">

                                    <div class="panel-heading"><?php // echo $LANG['ssh2'];
                                                                ?></div>

                                    <div class="panel-body">

                            <?php // if ($ssh2) {
                            ?>

                                            <i class="fa fa-check" aria-hidden="true"></i> <?php // echo $LANG['ssh2_meets'];
                                                                                            ?>

                            <?php // } else {
                            ?>

                                            <i class="fa fa-times" aria-hidden="true"></i> <?php // echo $LANG['ssh2_doesnot_meets'];
                                                                                            ?>

                            <?php // }
                            ?>

                                    </div>

                                </div>

                            </div>-->

                        </div>

                    </div>

                </div>

                <?php
                if (file_exists(dirname(__DIR__) . '/includes/footer.php'))
                    require_once dirname(__DIR__) . '/includes/footer.php';
                ?>



            </div>

        </div>

    </div>

</div>