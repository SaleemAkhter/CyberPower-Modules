<?php
if (!defined("WHMCS")) die("This file cannot be accessed directly");

$_LANG['servername'] = 'Server Name';
$_LANG['servertype'] = 'Server Type';
$_LANG['volume'] = 'Volume';
$_LANG['osimage'] = 'Operation System Image';
$_LANG['datacentercode'] = 'Datacenter Code';
$_LANG['serverNetDetail'] = 'Server &amp; Network Details';
$_LANG['disklocal'] = 'Disk local:';
$_LANG['reboot'] = 'Reboot';
$_LANG['shutdown'] = 'Shutdown';
$_LANG['reset'] = 'Reset';
$_LANG['resetRootPasswd'] = 'Reset Root Password';
$_LANG['start'] = 'Start';
$_LANG['bandwidthUsage'] = 'Bandwidth Usage';
$_LANG['lastUpdate'] = 'Last Updated ';
$_LANG['snapshots'] = 'SNAPSHOTS';
$_LANG['notInPackage'] = 'This Addon is not in your package!';
$_LANG['takesnapshot'] = 'Take Snapshot';
$_LANG['snapshotCost'] = 'Snapshots cost';
$_LANG['operatingSystem'] = 'Operating System ';
$_LANG['datacenter'] = 'Datacenter';
$_LANG['city'] = 'City';
$_LANG['country'] = 'Country ';
$_LANG['rescue'] = 'RESCUE';
$_LANG['addonNotInpackage'] = 'This Addon is not in your package!';
$_LANG['snapshotNotSubscribeDesc'] = 'Snapshots should be ordred as additional Addons';
$_LANG['snapshotSubscribeDesc'] = 'Snapshots are instant copies of your servers disks.<br>You can create a new server from a snapshot and even transfer them to a different project.<br>We recommend that you power down your server before taking a snapshot to ensure data consistency.';
$_LANG['rescueContainerDesc'] = 'The rescue system is a network based environment and can be used to fix issues preventing';
$_LANG['enableRescue'] = 'ENABLE RESCUE';
$_LANG['disableRescue'] = 'DISABLE RESCUE';
$_LANG['backupandSnapshot'] = 'BACKUPS &amp; SNAPSHOTS';
$_LANG['backupbtnText'] = 'Backup ';
$_LANG['snapshotbtnText'] = 'Snapshots ';
$_LANG['snapAndBackupDescription'] = 'Description';
$_LANG['snapAndBackupCreated'] = 'Created';
$_LANG['snapAndBackupDiskSize'] = 'Disk Size';
$_LANG['snapAndBackupStatus'] = 'Status';
$_LANG['rebuild'] = 'REBUILD';
$_LANG['rebuildContainerText'] = 'Rebuilding your server will power it down and overwrite its disk with the image you select. All previous data on the disk will be lost!';
$_LANG['noneSelected'] = 'None';
$_LANG['graphtext'] = 'Graphs';
$_LANG['graphlivetext'] = 'Live';
$_LANG['graph12hrtext'] = '12 hrs';
$_LANG['graph24hrtext'] = '24 hrs';
$_LANG['graph1weektext'] = '1 week';
$_LANG['graph30daystext'] = '30 days';
$_LANG['rebootModalHeading'] = 'SERVER REBOOT';
$_LANG['rebootModaltext'] = 'Are you sure you want to reboot this server?';
$_LANG['cancelText'] = 'CANCEL';
$_LANG['closeText'] = 'Close';
$_LANG['shutdownModalHeading'] = 'SERVER SHUTDOWN';
$_LANG['shutdownModaltext'] = 'Are you sure you want to shutdown this server?';
$_LANG['resetModalHeading'] = 'SERVER RESET';
$_LANG['resetModaltext'] = 'Are you sure you want to power off this server? This action may cause data loss.';
$_LANG['resetpassModalHeading'] = 'RESET ROOT PASSWORD';
$_LANG['resetpassModaltext'] = 'This will set a new root password. If you removed the qemu-guest agent from your server, this operation will fail.';
$_LANG['resetpassSuccessModalHeading'] = 'ROOT PASSWORD RESET SUCCESSFULL';
$_LANG['resetpassSuccessModaltext'] = 'Your Server\'s new root password is send to your registered E-mail ID. Thank you!';
$_LANG['serverSnapshotModalHeading'] = 'CREATE SNAPSHOT IMAGE OF SERVER';
$_LANG['serverSnapshotModalText'] = 'Snapshots are instant copies of your servers disks.You can create a new server from a snapshot and even transfer them to a different project.
We recommend that you power down your server before taking a snapshot to ensure data consistency.Snapshots cost €0.012/GB/month (incl. 20 % VAT).';
$_LANG['createSnapshot']= 'CREATE SNAPSHOT';
$_LANG['disableRescueModaltext'] = 'Disables the Server Rescue System for a server. This makes a server start from its disks on next reboot.Rescue Mode is automatically disabled when you first boot into it or if you do not use it for 60 minutes.Disabling rescue mode will not reboot your server — you will have to do this yourself.';
$_LANG['rebuildModalHeading'] = 'WARNING!';
$_LANG['rebuildModalText'] = 'Rebuilding your server will power it down and overwrite its disk with the image you select. All previous data on the disk will be lost!!';
$_LANG['convertTosnapModalHeading'] = 'CONVERT TO SNAPSHOT';
$_LANG['convertTosnapModaltext'] = 'This will convert the backup into a permanent image. The Snapshot will cost €0.012/GB/month (incl. 20 % VAT). Our terms and conditions apply.';
$_LANG['deleteBkpSnapModalHeading'] = 'DELETE IMAGE';
$_LANG['deleteBkpSnapModaltext'] = 'Are you sure you want to delete this image?';
$_LANG['deleteText'] = 'DELETE';
$_LANG['enableProModalHeading'] = 'ENABLE PROTECTION';
$_LANG['enableProModalText'] = 'A protected snapshot cannot be deleted as long as the protection is active';


$_LANG['statusShutdownRun'] = 'SHUTDOWN RUNNING..';
$_LANG['statusRebootRun'] = 'REBOOT RUNNING..';
$_LANG['statusResetRun'] = 'RESET RUNNING..';
$_LANG['statusResetPassRun'] = 'RESETTING PASSWORD..';
$_LANG['statusSnapshotRun'] = 'SNAPSHOT RUNNING..';
$_LANG['statusPowerOnRun'] = 'POWERON RUNNING..';
$_LANG['statusOff'] = 'OFF';
$_LANG['statusOn'] = 'ON';

$_LANG['success'] = 'Success! ';
$_LANG['successfully'] = ' successfully';
$_LANG['successDone'] = ' successfully done';
$_LANG['error'] = 'Error! ';

$_LANG['successAlertRescueEnabled'] = 'Rescue enabled successfully';
$_LANG['successAlertRescueDisabled'] ='Rescue disabled successfully';
$_LANG['successAlertServSnap'] = 'Server snapshot created ';
$_LANG['successAlertRootPassReset'] = 'Root password reset ';


$_LANG['disableProtection'] = 'DISABLE PROTECTION';
$_LANG['enableProtection'] = 'ENABLE PROTECTION';
$_LANG['imgDelete'] = ' IMAGE DELETE ';

$_LANG['imgProtecEnable'] = ' IMAGE PROTECTION ENABLE ';
$_LANG['imgProtecDisable'] = ' IMAGE PROTECTION DISABLE ';
$_LANG['changesDesc'] = 'Change description';
$_LANG['delete'] = 'Delete';

$_LANG['read'] = 'read';
$_LANG['write'] = 'write';
$_LANG['diskiops'] = 'DISK IOPS';
$_LANG['diskthroughput'] = 'DISK THROUGHPUT';
$_LANG['networkpps'] = 'NETWORK PPS';
$_LANG['networktraffic'] = 'NETWORK TRAFFIC';
$_LANG['netIn'] = 'in';
$_LANG['netOut'] = 'out';

$_LANG['floatingIP'] = 'Floating IPs';
$_LANG['addfloatingIP'] = ' Add Floating IPs';
$_LANG['addfloatingIPsuccess'] = ' Floating IP added';
$_LANG['reverseDNS'] = 'Reverse DNS';
$_LANG['homeLocation'] = 'Home Location';
$_LANG['fIpInstrModalHeading'] = 'CONFIGURE FLOATING IP';
$_LANG['fIpInstrModalbodyText1'] = 'The floating IP has been successfully assigned. You now need to configure it on your server in order for it to work.';
$_LANG['fIpInstrModalbodyText2'] = 'Command for temporary configuration';
$_LANG['fIpInstrModalbodyText3'] = '$ sudo ip addr add <span id="ipAddress">116.202.4.194<span> dev eth0';
$_LANG['fIpInstrModalbodyText4'] = 'A temporary configuration will only work until the next reboot.';
$_LANG['fIpInstrModalfooter'] = 'GOT IT';

$_LANG['fIpProtectModalbodyText1'] = 'A protected floating IP cannot be deleted as long as the protection is active.';

$_LANG['fIpRevDnsModalHeading'] = 'EDIT REVERSE DNS';
$_LANG['fIpRevDnsModalbody'] = 'You are editing the Reverse DNS entry of the IP';
$_LANG['imgNotAvail'] = 'Image not Available';
$_LANG['servRebuild'] = 'SERVER REBUILD ';

$_LANG['showInstruc'] = 'Show Instruction';
$_LANG['resetRevDns'] = 'Reset Reverse DNS';

$_LANG['noFloatingIPBuyed'] = 'No Floating IPs are found';
$_LANG['totalFloatingIP'] = 'Total Floating IP :';
$_LANG['usedFloatingIP'] = 'Used Floating IP :';
$_LANG['typeOfFloatIP'] = 'Type of the Floating IP';
$_LANG['flpunassign'] = 'Unassign IP';
$_LANG['flpassign'] = 'Assign IP';
$_LANG['buyAdditionFloatIP'] = 'No additional floating IPs available. Please buy additional floating IPs';
$_LANG['numbOfFLP'] = 'Number of floating IPs';
$_LANG['priceperFLP'] = 'Price Per Floating IP';
$_LANG['total'] = 'Total';

$_LANG['orderSuccesPlaceModalHead'] = 'Order Successfully placed!';
$_LANG['orderSuccesPlaceModalBody'] = 'Your order is successfully place and invoice is generated <br>for your order is sent to your registered email address';
$_LANG['tableAction'] = 'Actions';

$_LANG['console'] = "VNC";
$_LANG['isoimages'] = "ISO Images";
$_LANG['mount'] = "MOUNT";
$_LANG['isoContainerText'] = "Mounting an ISO Image will attach it to the virtual drive in your server. <br/> Rebooting your server while a ISO image is mounted will cause it to boot from the image. After rebooting the server you can access our web console to complete the installation. Some images may require you to press a key in the console during boot, otherwise the server will boot from disk again.";
$_LANG['unmountbtn'] = "UNMOUNT";
$_LANG['unmountModalHeading'] = 'UNMOUNT ISO';
$_LANG['unmountModaltext'] = "UNMOUNT attched ISO image";



