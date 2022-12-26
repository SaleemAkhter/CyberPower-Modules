<?php

use Illuminate\Database\Capsule\Manager as Capsule;
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
                        <h3><?php echo $LANG['migrate_vms']; ?></h3>
                        <p>
                            <?php echo $LANG['vmmigratevmsdesc']; ?>&nbsp;
                        </p>
                    </div>
                    <div id="ajaxres"></div>
                    <div class="tablebg">
                        <table id="sortabletbl0" class="datatable partnerdetail_table" width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tbody>
                                <tr>
                                    <th width="10%"><?php echo $LANG['vmselectuser']; ?></th>
                                    <th width="10%"><?php echo $LANG['vmtemplates']; ?></th>
                                    <th width="15%"><?php echo $LANG['vm_from_hostname']; ?></th>
                                    <th width="12%"><?php echo $LANG['vm_to_hostname']; ?></th>
                                    <th width="15%"><?php echo $LANG['vm_r_pool']; ?></th>
                                    <th width="8%"><?php echo $LANG['vm_migrate_reason']; ?></th>
                                    <th width="20%"><?php echo $LANG['action']; ?></th>
                                </tr>
                                <?php
                                if (isset($_GET['id']) && $_GET['id'] != '')
                                    $dataArr = Capsule::table('mod_vmware_migration_list')->where('id', filter_var($_GET['id'], FILTER_SANITIZE_STRING))->get();
                                else
                                    $dataArr = Capsule::table('mod_vmware_migration_list')->get();

                                foreach ($dataArr as $data) {
                                    $data = (array) $data;
                                    ?>
                                    <tr>
                                        <td width="10%"><a target="_blank" href="clientsservices.php?userid=<?php echo $data['uid']; ?>&id=<?php echo $data['sid']; ?>"><?php echo $data['user']; ?></a></td>
                                        <td width="10%"><?php echo $data['vmname']; ?></td>
                                        <td width="15%"><?php echo $data['from_host']; ?></td>
                                        <td width="12%"><?php echo $data['to_host']; ?></td>
                                        <td width="15%"><?php echo $data['r_pool']; ?></td>
                                        <td width="8%">
                                            <?php if ($data['status'] == 2) { ?><img src="images/info.gif" onclick="showReason(this);" style="cursor: pointer;" reason="<?php echo $data['reason']; ?>"><?php } else { ?>
                                                --
                                            <?php } ?>
                                        </td>
                                        <td width="20%">
                                            <?php if ($data['status'] == 0) { ?>
                                                <button onclick="approveReq(this, '<?php echo $data['id']; ?>', '<?php echo $modulelink; ?>', '<?php echo $data['vmname']; ?>', '<?php echo $data['to_host']; ?>', '<?php echo $data['r_pool']; ?>', '<?php echo $data['server_id']; ?>', '<?php echo $data['sid']; ?>', '<?php echo $data['datacenter']; ?>', '<?php echo $LANG['approve']; ?>');" class="approve"><?php echo $LANG['approve']; ?></button>&nbsp;&nbsp; 
                                                <button onclick="declineReq(this, '<?php echo $data['id']; ?>', '<?php echo $data['vmname']; ?>', '<?php echo $modulelink; ?>', '<?php echo $data['sid']; ?>');" class="decline"><?php echo $LANG['decline']; ?></button> 

                                            <?php } elseif ($data['status'] == 1) {
                                                ?>
                                                <button class="approve" style="cursor: auto;"><?php echo $LANG['approved']; ?></button> 
                                            <?php } elseif ($data['status'] == 2) { ?>
                                                <button class="decline" style="cursor: auto;"><?php echo $LANG['declined']; ?></button> 
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>        
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

<div class="modal fade" id="addAdimUser" tabindex="-1" role="dialog" aria-labelledby="addAdimUser" aria-hidden="false" style="padding-right: 15px;">
    <div class="modal-dialog">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only"><?php echo $LANG['vm_migrate_reason']; ?></span>
                </button>
                <h4 class="modal-title" id="popupHeading"><?php echo $LANG['vm_migrate_reason']; ?></h4>
            </div>
            <div class="modal-body panel-body" id="popupBody">
                <input type="hidden" name="vmname" value="" id="declineVm">
                <input type="hidden" name="id" value="" id="declineDbId">
                <input type="hidden" name="modulelink" value="<?php echo $modulelink; ?>" id="declineMdLink">
                <input type="hidden" name="sid" value="" id="declineSid">
                <div id="decresult"></div>
                <div class="reason">
                    <span><?php echo $LANG['vm_migrate_reason']; ?></span>
                    <textarea name="reason" cols="50" rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer panel-footer">
                <button class="decline_vm" onclick="submitDeclineReq(this);"><?php echo $LANG['submit']; ?></button>&nbsp;&nbsp;
                <button type="button" id="doDelete-cancel" class="btn btn-default" data-dismiss="modal">
                    <?php echo $LANG['close']; ?>   
                </button>
            </div>
        </div>
    </div>
</div>