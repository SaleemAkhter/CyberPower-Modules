<?php

// ---------------------------------------------------- BASIC  ---------------------------------------------------------

$_LANG['noDataAvailable']                                      = 'No Data Available';
$_LANG['searchPlacecholder']                                  = 'Search...';
$_LANG['changesHasBeenSaved']                                 = 'Changes have been saved successfully';
$_LANG['actionCannotBeFound']                                 = 'The action cannot be found';
$_LANG['addonCA']['pageNotFound']                             = 'Page not found';
$_LANG['FormValidators']['thisFieldCannotBeEmpty']            = 'This field cannot be empty';
$_LANG['FormValidators']['PleaseProvideANumericValueBetween'] = 'Please provide a number between 0 and 999';
$_LANG['FormValidators']['invalidDomain']                     = 'The domain is invalid';
$_LANG['FormValidators']['invalidIPv6']                       = 'The IP address is invalid';
$_LANG['FormValidators']['InvalidIP']                           = 'The IP address is invalid';

$_LANG['token']                                               = 'Token';
$_LANG['emptyServerGroup']                                    = "You need to select the server group from the dropdown menu first and save product configuration";
$_LANG['serverIsNotEmpty']                                    = "The server ID field is not empty";
$_LANG['serverMustOff']                                       = "If you want to change the type of server, your server must be off.";
$_LANG['downgradeError']                                      = "You cannot downgrade the type of your server.";
$_LANG['auto']                                                = "Auto";
$_LANG['permissionsStorage']                                  = ':storage_path: settings are not sufficient. Please set up permissions to the \'storage\' folder as writable.';
$_LANG['invalidServerType']                                   = "You need to select the server group from the dropdown menu first.";




// ---------------------------------------------------- Module Configuration  ---------------------------------------------------------
$_LANG['serverAA']['productPage']['connectionProblem'] = "Server connection problem. Please check the configuration and try again.";
$_LANG['serverAA']['productPage']['configurationForm']  = "Configuration";
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[type]']['packageconfigoption[type]']  = 'Type';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[datacenter]']['packageconfigoption[datacenter]']  = 'Data Center';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[image]']['packageconfigoption[image]'] = 'Image';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[location]']['packageconfigoption[location]'] = 'Location';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[volume]']['packageconfigoption[volume]'] = 'Additional Volume Size [GB]';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[backups]']['packageconfigoption[backups]'] = 'Backups';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[userdata]']['packageconfigoption[userdata]'] = 'User Data';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[floatingIpsNumber]']['packageconfigoption[floatingIpsNumber]'] = 'Number of Floating IPs';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[enableBackups]']['packageconfigoption[enableBackups]'] = 'Enable Backups';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaBackups]']['packageconfigoption[clientAreaBackups]'] = 'Backups';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaGraphs]']['packageconfigoption[clientAreaGraphs]'] = 'Graphs';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaGraphs]']['clientAreaGraphsDescription'] = 'Enable to grant clients the option to access the "Graphs" section in the client area.';

$_LANG['serverAA']['productPage']['doNotUse'] = 'Do Not Use';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[backups]']['backupsDescription'] = 'BackupsDescription';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[location]']['locationDescription']                  = 'Choose a preferred location from the list.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[datacenter]']['datacenterDescription']              = 'Choose a data center region. If you do not select anything in this field, the location will not be considered.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[type]']['typeDescription']                          = 'Choose a type from the available that will be used on the server.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[image]']['imageDescription']                        = 'Select an image of the system that will be installed on the server.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[floatingIpsNumber]']['floatingIpsNumberDescription'] = 'Number of Floating IPs created for your server.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[enableBackups]']['enableBackupsDescription']       = 'Enable creating backups for your server.';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaBackups]']['clientAreaBackupsDescription'] = 'Enable to grant clients the option to access the "Backups" section in the client area.';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaFloatingIPs]']['clientAreaFloatingIPsDescription'] = 'Enable to grant clients the option to access the "Floating IPs" section in the client area.';

$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[backups]']['backupDescription']                    = 'Enables automatic system-level backups.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[volume]']['volumeDescription']                     = 'Allows you to create and attach additional SSD storage volume to your server in the provided size (in GB). If left empty or entered 0, then the volume will not be created or attached.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[userdata]']['userdatadescription']                 = 'Choose the file or Bash script which may be used to configure the server on first boot. The script has to be located in the ".../modules/servers/ HetznerVPS/storage/ userDataFiles/" directory.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[randomDomainPrefix]']['packageconfigoption[randomDomainPrefix]'] = 'Random Domain Prefix';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[randomDomainPrefix]']['randomDomainPrefixDescription'] = 'Enter the domain prefix that will be used when the domain is not provided. Only valid hostname characters are allowed. (a-z, A-Z, 0-9, . and -)';

#---------- ----------#

$_LANG['doNotUse'] = 'Do Not Use';
$_LANG['featuresForm'] = 'Client Area Features';
$_LANG['cronInformation'] = "Cron Commands Information";
$_LANG['connectionProblem'] = "Server connection problem. Please check the configuration and try again.";
$_LANG['configurationForm']  = "Configuration";
$_LANG['configurableOptions'] = 'Configurable Options';
$_LANG['configurableOptionsUpdated'] = 'Configurable options have been updated successfully';
$_LANG['configurableOptionsCreated'] = 'Configurable options have been generated successfully';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[type]']['packageconfigoption[type]']  = 'Type';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[datacenter]']['packageconfigoption[datacenter]']  = 'Data Center';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[image]']['packageconfigoption[image]'] = 'Image';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[location]']['packageconfigoption[location]'] = 'Location';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[volume]']['packageconfigoption[volume]'] = 'Additional Volume Size [GB]';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[backups]']['packageconfigoption[backups]'] = 'Backups';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[userdata]']['packageconfigoption[userdata]'] = 'User Data';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[floatingIpsNumber]']['packageconfigoption[floatingIpsNumber]'] = 'Number of Floating IPs';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[enableBackups]']['packageconfigoption[enableBackups]'] = 'Enable Backups';

$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[backups]']['backupsDescription'] = 'BackupsDescription';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[location]']['locationDescription']                  = 'Choose a preferred location from the list.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[datacenter]']['datacenterDescription']              = 'Choose a data center region. If you do not select anything in this field, the location will not be considered.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[type]']['typeDescription']                          = 'Choose a type from the available that will be used on the server.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[image]']['imageDescription']                        = 'Select an image of the system that will be installed on the server.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['leftSection']['packageconfigoption[floatingIpsNumber]']['floatingIpsNumberDescription'] = 'Number of Floating IPs created for your server.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[enableBackups]']['enableBackupsDescription']       = 'Enable creating backups for your server.';

$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[backups]']['backupDescription']                    = 'Enables automatic system-level backups.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[volume]']['volumeDescription']                     = 'Allows you to create and attach additional SSD storage volume to your server in the provided size (in GB). If left empty or entered 0, then the volume will not be created or attached.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[userdata]']['userdatadescription']                 = 'Choose the file or Bash script which may be used to configure the server on first boot. The script has to be located in the ".../modules/servers/ HetznerVPS/storage/ userDataFiles/" directory.';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[randomDomainPrefix]']['packageconfigoption[randomDomainPrefix]'] = 'Random Domain Prefix';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[randomDomainPrefix]']['randomDomainPrefixDescription'] = 'Enter the domain prefix that will be used when the domain is not provided. Only valid hostname characters are allowed. (a-z, A-Z, 0-9, . and -)';
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[snapshots]']['packageconfigoption[snapshots]'] = 'Snapshots  Limit [GB]'  ;
$_LANG['serverAA']['product']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[snapshots]']['snapshotsDescription'] = 'Specify snapshots size limit here. Set -1 for unlimited.';

$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaBackups]']['packageconfigoption[clientAreaBackups]'] = 'Backups';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaGraphs]']['packageconfigoption[clientAreaGraphs]'] = 'Graphs';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaGraphs]']['clientAreaGraphsDescription'] = 'Enable to grant clients the option to access the "Graphs" section in the client area.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaBackups]']['clientAreaBackupsDescription'] = 'Enable to grant clients the option to access the "Backups" area.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaFloatingIPs]']['packageconfigoption[clientAreaFloatingIPs]']       = 'Floating IPs';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaFloatingIPs]']['clientAreaFloatingIPsDescription'] = 'Enable to grant clients the option to access the "Floating IPs" in the client area.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaRebuild]']['packageconfigoption[clientAreaRebuild]'] = 'Rebuild';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaConsole]']['packageconfigoption[clientAreaConsole]'] = 'Console';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaRebuild]']['clientAreaRebuildDescription'] = 'Enable to grant clients the option to rebuild the virtual machine.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaConsole]']['clientAreaConsoleDescription'] = 'Enable to grant clients the option to open console for the virtual machine.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableImages][]']['packageconfigoption[clientAreaAvailableImages][]'] = 'Available Images To Rebuild';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableImages][]']['clientAreaAvailableImagesDescription'] = 'Select OS images from available to allow clients to use when rebuilding a VM. Please note that if you leave this field empty, then all available OS images will be displayed in the client area to rebuild.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaSnapshots]']['packageconfigoption[clientAreaSnapshots]'] = 'Snapshots';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaSnapshots]']['clientAreaSnapshotsDescription'] = 'Enable to grant clients the option to create snapshots.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaReverseDNS]']['packageconfigoption[clientAreaReverseDNS]']       = 'Reverse DNS';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaReverseDNS]']['clientAreaReverseDNSDescription']       = 'Enable if you want clients to access the "Reverse DNS" option.';

$_LANG['serverAA']['product']['mainContainer']['configurableOptions']['button']['createCOBaseModalButton'] = 'Create Configurable Options';
$_LANG['serverAA']['product']['mainContainer']['configurableOptions']['doNotUse'] = 'Do not use';

$_LANG['serverAA']['product']['createCOConfirmModal']['modal']['createCOConfirmModal'] = 'Create Configurable Options ';
$_LANG['serverAA']['product']['createCOConfirmModal']['baseAcceptButton']['title']     = 'Confirm';
$_LANG['serverAA']['product']['createCOConfirmModal']['baseCancelButton']['title']     = 'Cancel';

$_LANG['serverAA']['product']['configurableOptionExsits'] = "Configurable options have already been created";

$_LANG['serverAA']['product']['mainContainer']['dataTable']['serverinformationTable']                  = "Server Information";

$_LANG['serverAA']['product']['powerOnConfirmModal']['modal']['powerOnConfirmModal']                   = 'Power On';
$_LANG['serverAA']['product']['powerOnConfirmModal']['powerOnActionForm']['conforimPowerOn']           = 'Are you sure that you want to power on this virtual machine?';
$_LANG['serverAA']['product']['powerOnConfirmModal']['baseAcceptButton']['title']                      = 'Confirm';
$_LANG['serverAA']['product']['powerOnConfirmModal']['baseCancelButton']['title']                      = 'Cancel';

$_LANG['serverAA']['product']['powerOffConfirmModal']['modal']['powerOffConfirmModal']                 = 'Power Off';
$_LANG['serverAA']['product']['powerOffConfirmModal']['powerOffActionForm']['confirmPowerOff']         = 'Are you sure that you want to power off this virtual machine?';
$_LANG['serverAA']['product']['powerOffConfirmModal']['baseAcceptButton']['title']                     = 'Confirm';
$_LANG['serverAA']['product']['powerOffConfirmModal']['baseCancelButton']['title']                     = 'Cancel';

$_LANG['serverAA']['product']['shutdownConfirmModal']['modal']['shutdownConfirmModal']                 = 'Shut Down';
$_LANG['serverAA']['product']['shutdownConfirmModal']['shutdownActionForm']['confirmShutodown']        = 'Are you sure that you want to shut down this virtual machine?';
$_LANG['serverAA']['product']['shutdownConfirmModal']['baseAcceptButton']['title']                     = 'Confirm';
$_LANG['serverAA']['product']['shutdownConfirmModal']['baseCancelButton']['title']                     = 'Cancel';

$_LANG['serverAA']['product']['rebootConfirmModal']['modal']['rebootConfirmModal']                     = 'Reboot';
$_LANG['serverAA']['product']['rebootConfirmModal']['rebootActionForm']['conforimReboot']              = 'Are you sure that you want to reboot this virtual machine?';
$_LANG['serverAA']['product']['rebootConfirmModal']['baseAcceptButton']['title']                       = 'Confirm';
$_LANG['serverAA']['product']['rebootConfirmModal']['baseCancelButton']['title']                       = 'Cancel';

$_LANG['serverAA']['product']['passwordResetModal']['modal']['passwordResetModal']                     = 'Reset Password';
$_LANG['serverAA']['product']['passwordResetModal']['passwrodResetActionForm']['confirmResetPassword'] = 'Are you sure that you want to reset the password?';
$_LANG['serverAA']['product']['passwordResetModal']['baseAcceptButton']['title']                       = 'Confirm';
$_LANG['serverAA']['product']['passwordResetModal']['baseCancelButton']['title']                       = 'Cancel';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaIsos]']['packageconfigoption[clientAreaIsos]'] = 'ISO Images';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaIsos]']['clientAreaIsosDescription'] = 'Enable to grant clients the option to mount ISO images to a virtual machine.';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaFirewalls]']['packageconfigoption[clientAreaFirewalls]'] = "Firewalls";
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaFirewalls]']['clientAreaFirewallsDescription'] = "Toggle to turn on firewall options on a virtual machine";
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableIsos][]']['packageconfigoption[clientAreaAvailableIsos][]'] = 'Available ISO Images';
$_LANG['serverAA']['product']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableIsos][]']['clientAreaAvailableIsosDescription'] = 'Select ISO images from available to allow clients to use when mounting ISO images to a VM. Please note that if you leave this field empty, then all available ISO Images will be displayed in the client area.';

$_LANG['serverAA']['product']['mainContainer']['backupsTable']['backupsTable']                         = "Backups";
$_LANG['serverAA']['product']['mainContainer']['backupsTable']['table']['description']                 = "Description";
$_LANG['serverAA']['product']['mainContainer']['backupsTable']['table']['created']                     = "Created";
$_LANG['serverAA']['product']['mainContainer']['backupsTable']['table']['imageSize']                   = "Image size";
$_LANG['serverAA']['product']['mainContainer']['backupsTable']['table']['status']                      = "Status";
$_LANG['serverAA']['product']['mainContainer']['backupsTable']['restoreButton']['button']['restoreButton'] = 'Restore';

$_LANG['serverAA']['adminServicesTabFields']['restoreModal']['modal']['restoreModal']                              = 'Restore Backup';
$_LANG['serverAA']['adminServicesTabFields']['restoreModal']['restoreForm']['restoreConfirm']                      = 'Are you sure that you want to restore this backup? All previous data on the disk will be lost';
$_LANG['serverAA']['adminServicesTabFields']['restoreModal']['baseAcceptButton']['title']                          = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['restoreModal']['baseCancelButton']['title']                          = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['floatingIPsEditModal']['modal']['floatingIPsEditModal'] = 'Edit Floating IP';
$_LANG['serverAA']['adminServicesTabFields']['floatingIPsEditModal']['floatingIPsEditForm']['dns']['dns'] = 'Reverse DNS';
$_LANG['serverAA']['adminServicesTabFields']['floatingIPsEditModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['floatingIPsEditModal']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverAA']['product']['no'] = "No";
$_LANG['serverAA']['product']['yes'] = "Yes";
#---------- ----------#


$_LANG['auto']                                                                                                  = "Auto";

$_LANG['serverAA']['productPage']['configurableOptions']                                                       = 'Configurable Options';
$_LANG['serverAA']['productPage']['mainContainer']['configurableOptions']['button']['createCOBaseModalButton'] = 'Create Configurable Options';

$_LANG['serverAA']['productPage']['createCOConfirmModal']['modal']['createCOConfirmModal'] = 'Create Configurable Options ';
$_LANG['serverAA']['productPage']['createCOConfirmModal']['baseAcceptButton']['title']     = 'Confirm';
$_LANG['serverAA']['productPage']['createCOConfirmModal']['baseCancelButton']['title']     = 'Cancel';

$_LANG['configurableOptionsCreate']                           = "Configurable options have been created successfully";
$_LANG['configurableOptionsUpdate']                           = "Configurable options have been updated successfully";
$_LANG['serverAA']['productPage']['configurableOptionExsits'] = "Configurable options have already been created";

$_LANG['serverAA']['productPage']['mainContainer']['configurableOptions']['doNotUse'] = 'Do not use';

$_LANG['serverAA']['product']['featuresForm'] = 'Client Area Features';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaRebuild]']['packageconfigoption[clientAreaRebuild]'] = 'Rebuild';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaConsole]']['packageconfigoption[clientAreaConsole]'] = 'Console';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaRebuild]']['clientAreaRebuildDescription'] = 'Enable to grant clients the option to rebuild the virtual machine.';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaConsole]']['clientAreaConsoleDescription'] = 'Enable to grant clients the option to open console for the virtual machine.';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableImages][]']['packageconfigoption[clientAreaAvailableImages][]'] = 'Available Images To Rebuild';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaAvailableImages][]']['clientAreaAvailableImagesDescription'] = 'Select OS images from available to allow clients to use when rebuilding a VM. Please note that if you leave this field empty, then all available OS images will be displayed in the client area to rebuild.';
// ---------------------------------------------------- Admin Area  ---------------------------------------------------------
$_LANG['volumeMustBeHigherThanTen'] = "Additional volume size must be higher than 10 GB";
$_LANG['cannotCreateVolume']        = "Cannot create the volume, probably the volume with the same name already exists. Please change the hostname and try again.";
$_LANG['cannotCreateKey']           = "Cannot create the new SSH Key, probably the key with the same name already exists. Please change the hostname and try again.";

$_LANG['serverAA']['productPage']['mainContainer']['dataTable']['serverinformationTable']                               = "Server Information";

$_LANG['serverAA']['product']['mainContainer']['optionsWidget']['optionsWidgetTitle']                                   = 'Configurable Options';
$_LANG['serverAA']['product']['mainContainer']['optionsWidget']['addOptionsButton']['button']['addOptionButtonsTitle']  = 'Create Configurable Options';
$_LANG['serverAA']['configOptions']['createCOConfirmModal']['modal']['createCOConfirmModal']                            = 'Create Configurable Option';
$_LANG['serverAA']['configOptions']['createCOConfirmModal']['createConfigurableAction']['configurableOptionsNameInfo']  = 'Below you can choose which configurable options will be generated for this product. Please note that these options are divided into two parts separated by a | sign, where the part on the left indicates the sent variable and the part on the right the friendly name displayed to customers. After generating these options you can edit the friendly part on the right, but not the variable part on the left. More information about configurable options and their editing can be found :configurableOptionsNameUrl:.';
$_LANG['serverAA']['configOptions']['createCOConfirmModal']['baseAcceptButton']['title']                                = 'Confirm';
$_LANG['serverAA']['configOptions']['createCOConfirmModal']['baseCancelButton']['title']                                = 'Cancel';

$_LANG['serverAA']['configOptions']['addOptionsModal']['modal']['addOptionsModalTitle']                                 = 'Create Configurable Option';
$_LANG['serverAA']['configOptions']['addOptionsModal']['addOptionsForm']['configurableOptionsNameInfo']                 = 'Below you can choose which configurable options will be generated for this product. Please note that these options are divided into two parts separated by a | sign, where the part on the left indicates the sent variable and the part on the right the friendly name displayed to customers. After generating these options you can edit the friendly part on the right, but not the variable part on the left. More information about configurable options and their editing can be found :configurableOptionsNameUrl:.';
$_LANG['serverAA']['configOptions']['addOptionsModal']['baseAcceptButton']['title']                                     = 'Confirm';
$_LANG['serverAA']['configOptions']['addOptionsModal']['baseCancelButton']['title']                                     = 'Cancel';

//--
$_LANG['serverAA']['adminServicesTabFields']['powerOnConfirm']['modal']['powerOnConfirm']                               = 'Power On';
$_LANG['serverAA']['adminServicesTabFields']['powerOnConfirm']['powerOnActionForm']['confirmPowerOn']                   = 'Are you sure that you want to power on this virtual machine?';
$_LANG['serverAA']['adminServicesTabFields']['powerOnConfirm']['baseAcceptButton']['title']                             = 'Power On';
$_LANG['serverAA']['adminServicesTabFields']['powerOnConfirm']['baseCancelButton']['title']                             = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['powerOffConfirmModal']['modal']['powerOffConfirmModal']                   = 'Power Off';
$_LANG['serverAA']['adminServicesTabFields']['powerOffConfirmModal']['powerOffActionForm']['confirmPowerOff']           = 'Are you sure that you want to power off this virtual machine?';
$_LANG['serverAA']['adminServicesTabFields']['powerOffConfirmModal']['baseAcceptButton']['title']                       = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['powerOffConfirmModal']['baseCancelButton']['title']                       = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['shutdownConfirmModal']['modal']['shutdownConfirmModal']                   = 'Shut Down';
$_LANG['serverAA']['adminServicesTabFields']['shutdownConfirmModal']['shutdownActionForm']['confirmShutodown']          = 'Are you sure that you want to shut down this virtual machine?';
$_LANG['serverAA']['adminServicesTabFields']['shutdownConfirmModal']['baseAcceptButton']['title']                       = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['shutdownConfirmModal']['baseCancelButton']['title']                       = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['rebootConfirmModal']['modal']['rebootConfirmModal']                       = 'Reboot Virtual Machine';
$_LANG['serverAA']['adminServicesTabFields']['rebootConfirmModal']['rebootActionForm']['confirmReboot']                 = 'Are you sure that you want to reboot this virtual machine?';
$_LANG['serverAA']['adminServicesTabFields']['rebootConfirmModal']['baseAcceptButton']['title']                         = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['rebootConfirmModal']['baseCancelButton']['title']                         = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['passwordResetModal']['modal']['passwordResetModal']                       = 'Reset Password';
$_LANG['serverAA']['adminServicesTabFields']['passwordResetModal']['passwrodResetActionForm']['confirmResetPassword']   = 'Are you sure that you want to reset the password?';
$_LANG['serverAA']['adminServicesTabFields']['passwordResetModal']['baseAcceptButton']['title']                         = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['passwordResetModal']['baseCancelButton']['title']                         = 'Cancel';

$_LANG['serverAA']['home']['mainContainer']['serverinformationTable']['serverinformationTable']                         = 'Server Information';

$_LANG['serverAA']['home']['mainContainer']['rebuildTable']['rebuildTable'] = 'Rebuild Virtual Machine';
$_LANG['serverAA']['home']['mainContainer']['rebuildTable']['table']['distribution'] = 'Distribution';
$_LANG['serverAA']['home']['mainContainer']['rebuildTable']['table']['name'] = 'Name';
$_LANG['serverAA']['home']['mainContainer']['rebuildTable']['confirmRebuildButton']['button']['confirmButton'] = 'Rebuild';

$_LANG['serverAA']['home']['mainContainer']['isosTable']['isosTable'] = 'ISO Images';
$_LANG['serverAA']['home']['mainContainer']['isosTable']['table']['description'] = 'Description';
$_LANG['serverAA']['home']['mainContainer']['isosTable']['mountButton']['button']['mountButton'] = 'Mount ISO Image';
$_LANG['serverAA']['home']['mainContainer']['isosTable']['unmountButton']['button']['unmountButton'] = 'Unmount ISO Image';

$_LANG['serverAA']['home']['mainContainer']['floatingIPsTable']['floatingIPsTable'] = 'Floating IPs';
$_LANG['serverAA']['home']['mainContainer']['floatingIPsTable']['table']['ip'] = 'Ip';
$_LANG['serverAA']['home']['mainContainer']['floatingIPsTable']['table']['dns'] = 'Dns';
$_LANG['serverAA']['home']['mainContainer']['floatingIPsTable']['floatingIPsEditButton']['button']['floatingIPsEditButton'] = 'Edit';

$_LANG['serverAA']['home']['mainContainer']['backupsTable']['backupsTable'] = 'Backups';
$_LANG['serverAA']['home']['mainContainer']['backupsTable']['table']['status'] = 'Status';
$_LANG['serverAA']['home']['mainContainer']['backupsTable']['table']['created'] = 'Created';
$_LANG['serverAA']['home']['mainContainer']['backupsTable']['table']['imageSize'] = 'Image Size';
$_LANG['serverAA']['home']['mainContainer']['backupsTable']['table']['description'] = 'Description';
$_LANG['serverAA']['home']['mainContainer']['backupsTable']['restoreButton']['button']['restoreButton'] = 'Restore';

$_LANG['serverAA']['home']['mainContainer']['serverinformationTable']['table']['name'] = 'Name';
$_LANG['serverAA']['home']['mainContainer']['serverinformationTable']['table']['value'] = 'Value';

$_LANG['serverAA']['adminServicesTabFields']['confirmRebuildModal']['modal']['confirmRebuildModal'] = 'Rebuild Virtual Machine';
$_LANG['serverAA']['adminServicesTabFields']['confirmRebuildModal']['rebuildConfirmForm']['rebuildConfirm'] = 'Are you sure that you want to rebuild this virtual machine?';
$_LANG['serverAA']['adminServicesTabFields']['confirmRebuildModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['serverAA']['adminServicesTabFields']['confirmRebuildModal']['baseCancelButton']['title'] = 'Cancel';

// ---------------------------------------------------- Client Area  ---------------------------------------------------------
$_LANG['serverCA']['home']['manageHeader']   = "Service Actions";
$_LANG['serverCA']['home']['pagesHeader']    = "Service Management";
$_LANG['serverCA']['iconTitle']['rebuild']   = "Rebuild";
$_LANG['serverCA']['iconTitle']['console']   = "Console";
$_LANG['serverCA']['iconTitle']['reverseDNS']   = "Reverse DNS";
$_LANG['serverCA']['iconTitle']['floatingIPs']  = "Floating IPs";
$_LANG['serverCA']['iconTitle']['firewalls']    = "Firewalls";
$_LANG['serverCA']['iconTitle']['firewall']     = "Firewall";

$_LANG['wrongConfirmText']         = "Wrong confirmation text";
$_LANG['rebuildFromImageStart']    = "The process of rebuilding a virtual machine has been started successfully. It may take a few minutes.";
$_LANG['rebootStarted']            = "The virtual machine has been rebooted successfully";
$_LANG['shutdownStarted']          = "The virtual machine has been shut down successfully";
$_LANG['powerOnStarted']           = "The virtual machine has been powered on successfully";
$_LANG['powerOffStarted']          = "The virtual machine has been powered off successfully";
$_LANG['passwordResetStarted']     = "The password has been reset successfully. The new password is available in a password line in the Server Information table.";
$_LANG['editReverseDNSSuccess']    = "The selected reverse DNS has been edited successfully";
$_LANG['addReverseDNSSuccess']     = "The reverse DNS has been created successfully";

$_LANG['addonCA']['errorPage']['error']             = 'Error';
$_LANG['addonCA']['errorPage']['title']             = 'Unexpected Error';
$_LANG['addonCA']['errorPage']['description']       = 'Something went wrong, please contact your administrator';
$_LANG['addonCA']['errorPage']['errorCode']         = 'Error Code';
$_LANG['addonCA']['errorPage']['errorToken']        = 'Error Token';
$_LANG['addonCA']['errorPage']['errorTime']         = 'Error Time';
$_LANG['addonCA']['errorPage']['errorMessage']      = 'Error Message';
$_LANG['addonCA']['errorPage']['button']            = 'Error Button';
$_LANG['errorCodeMessage']                          = 'Error Code Message';
// ---------------------------------------------------- Menu ---------------------------------------------------------
$_LANG['serverCA']['sidebarMenu']['mg-provisioning-module'] = "Manage Server";
$_LANG['serverCA']['sidebarMenu']['rebuild']                = "Rebuild";
$_LANG['serverCA']['sidebarMenu']['console']                = "Console";
$_LANG['serverCA']['sidebarMenu']['graphs']                 = 'Graphs';

$_LANG['managementHetznerVps']                              = 'Additional Tools';
$_LANG['rebuild']                                           = 'Rebuild';
$_LANG['console']                                           = 'Console';
$_LANG['isos']                                              = 'ISO Images';
$_LANG['snapshots']                                         = 'Snapshots';
$_LANG['reverseDNS']                                        = 'Reverse DNS';
$_LANG['floatingIPs']                                       = 'Floating IPs';
$_LANG['backups']                                           = 'Backups';
$_LANG['graphs']                                            = 'Graphs';
$_LANG['firewalls']                                         = 'Firewalls';
$_LANG['firewall']                                          = 'Firewall';


// ---------------------------------------------------- Service Information  ---------------------------------------------------------
$_LANG['serverCA']['home']['mainContainer']['dataTable']['serverinformationTable'] = "Server Information";

$_LANG['serviceInformation']['tableField']['status']         = "Status";
$_LANG['serviceInformation']['tableField']['name']           = "Hostname";
$_LANG['serviceInformation']['tableField']['memory']         = "Memory";
$_LANG['serviceInformation']['tableField']['disk']           = "Disk Size";
$_LANG['serviceInformation']['tableField']['cpu']            = "CPU Number";
$_LANG['serviceInformation']['tableField']['image']          = "Image";
$_LANG['serviceInformation']['tableField']['backups']        = "Backups";
$_LANG['serviceInformation']['tableField']['volumes']        = "Additional Volume Size";
$_LANG['serviceInformation']['tableField']['ivp4']	         = "IPv4";
$_LANG['serviceInformation']['tableField']['ivp6']	         = "IPv6";
$_LANG['serviceInformation']['tableField']['datacenter']     = "Data Center";
$_LANG['serviceInformation']['tableField']['location']       = "Location";
$_LANG['serviceInformation']['tableField']['password']       = "Password";

$_LANG['serverCA']['home']['no'] = "No";
$_LANG['serverCA']['home']['no'] = "Yes";

$_LANG['serverCA']['home']['mainContainer']['serverinformationTable']['serverinformationTable'] = 'Server Information';
$_LANG['serverCA']['home']['mainContainer']['serverinformationTable']['table']['name'] = '';
$_LANG['serverCA']['home']['mainContainer']['serverinformationTable']['table']['value'] = '';

// ---------------------------------------------------- Service Actions  ---------------------------------------------------------
$_LANG['buttons']['actions']['powerOnButton']       = 'Power On Machine';
$_LANG['buttons']['actions']['powerOffButton']      = 'Power Off Machine';
$_LANG['buttons']['actions']['shutdownButton']      = 'Shut Down Machine';
$_LANG['buttons']['actions']['rebootButton']        = 'Reboot Machine';
$_LANG['buttons']['actions']['passwordResetButton'] = 'Reset Password';

$_LANG['serverCA']['home']['powerOnConfirm']['modal']['powerOnConfirm']                 = 'Power On';
$_LANG['serverCA']['home']['powerOnConfirm']['powerOnActionForm']['confirmPowerOn']     = 'Are you sure that you want to power on this virtual machine?';
$_LANG['serverCA']['home']['powerOnConfirm']['baseAcceptButton']['title']               = 'Confirm';
$_LANG['serverCA']['home']['powerOnConfirm']['baseCancelButton']['title']               = 'Cancel';


$_LANG['serverCA']['home']['powerOffConfirmModal']['modal']['powerOffConfirmModal']         = 'Power Off';
$_LANG['serverCA']['home']['powerOffConfirmModal']['powerOffActionForm']['confirmPowerOff'] = 'Are you sure that you want to power off this virtual machine?';
$_LANG['serverCA']['home']['powerOffConfirmModal']['baseAcceptButton']['title']             = 'Confirm';
$_LANG['serverCA']['home']['powerOffConfirmModal']['baseCancelButton']['title']             = 'Cancel';



$_LANG['serverCA']['home']['passwordResetModal']['modal']['passwordResetModal']                     = 'Reset Password';
$_LANG['serverCA']['home']['passwordResetModal']['passwrodResetActionForm']['confirmResetPassword'] = 'Are you sure that you want to reset the password?';
$_LANG['serverCA']['home']['passwordResetModal']['baseAcceptButton']['title']                       = 'Confirm';
$_LANG['serverCA']['home']['passwordResetModal']['baseCancelButton']['title']                       = 'Cancel';


$_LANG['serverCA']['home']['rebootConfirmModal']['modal']['rebootConfirmModal']        = 'Reboot Virtual Machine';
$_LANG['serverCA']['home']['rebootConfirmModal']['rebootActionForm']['confirmReboot']  = 'Are you sure that you want to reboot this virtual machine?';
$_LANG['serverCA']['home']['rebootConfirmModal']['baseAcceptButton']['title']          = 'Confirm';
$_LANG['serverCA']['home']['rebootConfirmModal']['baseCancelButton']['title']          = 'Cancel';


$_LANG['serverCA']['home']['shutdownConfirmModal']['modal']['shutdownConfirmModal']          = 'Shut Down';
$_LANG['serverCA']['home']['shutdownConfirmModal']['shutdownActionForm']['confirmShutodown'] = 'Are you sure that you want to shut down this virtual machine?';
$_LANG['serverCA']['home']['shutdownConfirmModal']['baseAcceptButton']['title']              = 'Confirm';
$_LANG['serverCA']['home']['shutdownConfirmModal']['baseCancelButton']['title']              = 'Cancel';


$_LANG['serverCA']['iconTitle']['powerOnButton']       = "Power On";
$_LANG['serverCA']['iconTitle']['powerOffButton']      = "Power Off";
$_LANG['serverCA']['iconTitle']['shutdownButton']      = "Shut Down";
$_LANG['serverCA']['iconTitle']['rebootButton']        = "Reboot";
$_LANG['serverCA']['iconTitle']['passwordResetButton'] = "Reset Password";




// ---------------------------------------------------- Client Area Rebuild ---------------------------------------------------------

$_LANG['serverCA']['rebuild']['mainContainer']['rebuildTable']['rebuildTable']          = "Rebuild Virtual Machine";
$_LANG['serverCA']['rebuild']['mainContainer']['rebuildTable']['table']['id']           = "ID";
$_LANG['serverCA']['rebuild']['mainContainer']['rebuildTable']['table']['name']         = "Name";
$_LANG['serverCA']['rebuild']['mainContainer']['rebuildTable']['table']['distribution'] = "Distribution";

$_LANG['serverCA']['rebuild']['mainContainer']['rebuildTable']['confirmRebuildButton']['button']['confirmButton'] = "Rebuild";

$_LANG['serverCA']['rebuild']['confirmRebuildModal']['modal']['confirmRebuildModal']         = 'Rebuild Virtual Machine';
$_LANG['serverCA']['rebuild']['confirmRebuildModal']['rebuildConfirmForm']['rebuildConfirm'] = 'Rebuilding your server will power it down and overwrite its disk with the image you select. All previous data on the disk will be lost!<br /><br />Are you sure that you want to rebuild this virtual machine?';
$_LANG['serverCA']['rebuild']['confirmRebuildModal']['baseAcceptButton']['title']            = 'Confirm';
$_LANG['serverCA']['rebuild']['confirmRebuildModal']['baseCancelButton']['title']            = 'Cancel';


//-------------------------------------------------- Client Area Backups -------------------------------------------------------------
$_LANG['serverCA']['sidebarMenu']['backups']                                                                                                     = "Backups";

$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['backupsTable']                                                                   = "Backups";
$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['table']['description']                                                           = "Description";
$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['table']['created']                                                               = "Created";
$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['table']['imageSize']                                                             = "Image Size";
$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['table']['status']                                                                = "Status";
$_LANG['serverCA']['backups']['mainContainer']['backupsTable']['restoreButton']['button']['restoreButton']                                       = "Restore";

$_LANG['serverCA']['backups']['restoreModal']['modal']['restoreModal']                                                                           = 'Restore Backup';
$_LANG['serverCA']['backups']['restoreModal']['restoreForm']['restoreConfirm']                                                                   = 'Are you sure that you want to restore this backup? All previous data on the disk will be lost';
$_LANG['serverCA']['backups']['restoreModal']['baseAcceptButton']['title']                                                                       = 'Confirm';
$_LANG['serverCA']['backups']['restoreModal']['baseCancelButton']['title']                                                                       = 'Cancel';
$_LANG['serverCA']['backups']['cronInformation']                                                                                                 = '';
$_LANG['serverCA']['backups']['mainContainer']['cronInformation']['cronTaskDescription']['desc']                                                 = 'Backups are everyday copies of the server’s disk. When the feature is enabled, the system will choose the time frame in which backups will be performed. Backups are assigned to a particular server and will be removed once this server is deleted. Please note that there are seven slots for backups within one server. When there are no empty slots and another backup is being created, then the oldest backup is removed.';
$_LANG['serverCA']['iconTitle']['backups']                                                                                                       = 'Backups';


$_LANG['restoreBackup']                                                                                                                          = 'The process of restoring backup  :description: has been started successfully. It may take a few minutes.';
$_LANG['errorBackupIsRestoring']                                                                                                                 = "You cannot restore the backup while another one is being restored. The server is locked.";
$_LANG['errorBackupIsCreating']                                                                                                                  = 'The selected backup is being created, please try again later.';
//-------------------------------------------------- Client Area Floating IPs --------------------------------------------------------
$_LANG['serverCA']['sidebarMenu']['floatingIPs']                                                                                                 = "Floating IPs";

$_LANG['serverCA']['floatingIPs']['mainContainer']['floatingIPsTable']['floatingIPsTable']                                                       = "Floating IPs";
$_LANG['serverCA']['floatingIPs']['mainContainer']['floatingIPsTable']['table']['ip']                                                            = "IP Address";
$_LANG['serverCA']['floatingIPs']['mainContainer']['floatingIPsTable']['table']['dns']                                                           = "Reverse DNS";
$_LANG['serverCA']['floatingIPs']['mainContainer']['floatingIPsTable']['floatingIPsEditButton']['button']['floatingIPsEditButton']               = "Edit Reverse DNS";


$_LANG['serverCA']['floatingIPs']['floatingIPsEditModal']['modal']['floatingIPsEditModal']                                                       = 'Edit Floating IP';
$_LANG['serverCA']['floatingIPs']['floatingIPsEditModal']['floatingIPsEditForm']['dns']['dns']                                                   = 'Reverse DNS';
$_LANG['serverCA']['floatingIPs']['floatingIPsEditModal']['baseAcceptButton']['title']                                                           = 'Save';
$_LANG['serverCA']['floatingIPs']['floatingIPsEditModal']['baseCancelButton']['title']                                                           = 'Cancel';

$_LANG['updateFloatingIP']                                                                                                                       = "The Floating IP :description: has been updated successfully";

$_LANG['serverCA']['floatingIPs']['deleteFloatingIPsModal']['modal']['deleteFloatingIPsModal']                                                   = 'Reset Floating IP';
$_LANG['serverCA']['floatingIPs']['deleteFloatingIPsModal']['deleteFloatingIPsForm']['confirmFloatingIPsDelete']                                 = 'Are you sure that you want to reset this reverse DNS?';
$_LANG['serverCA']['floatingIPs']['deleteFloatingIPsModal']['baseAcceptButton']['title']                                                         = 'Confirm';
$_LANG['serverCA']['floatingIPs']['deleteFloatingIPsModal']['baseCancelButton']['title']                                                         = 'Cancel';

$_LANG['serverAA']['productPage']['mainContainer']['dataTable']['table']['name'] = 'Name';
$_LANG['serverAA']['productPage']['mainContainer']['dataTable']['table']['value'] = 'Value';
$_LANG['serverAA']['productPage']['mainContainer']['floatingIPsTable']['floatingIPsTable'] = 'Floating IPs';
$_LANG['serverAA']['productPage']['mainContainer']['floatingIPsTable']['table']['ip'] = 'IP';
$_LANG['serverAA']['productPage']['mainContainer']['floatingIPsTable']['table']['dns'] = 'REVERSE DNS';
$_LANG['serverAA']['productPage']['mainContainer']['floatingIPsTable']['floatingIPsEditButton']['button']['floatingIPsEditButton'] = 'Edit';
$_LANG['serverAA']['productPage']['mainContainer']['floatingIPsTable']['floatingIPsEditButton']['button']['floatingIPsEditButton'] = 'Edit';
$_LANG['serverAA']['productPage']['floatingIPsEditModal']['modal']['floatingIPsEditModal'] = 'Edit Floating IP';
$_LANG['serverAA']['productPage']['floatingIPsEditModal']['floatingIPsEditForm']['dns']['dns'] = 'Reverse DNS';
$_LANG['serverAA']['productPage']['floatingIPsEditModal']['baseAcceptButton']['title'] = 'Save';
$_LANG['serverAA']['productPage']['floatingIPsEditModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaFloatingIPs]']['packageconfigoption[clientAreaFloatingIPs]']       = 'Floating IPs';
//-------------------------------------------------- Client Area Reverse DNS --------------------------------------------------------


$_LANG['serverCA']['sidebarMenu']['reverseDNS']                                                                                                 = "Reverse DNS";

$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['reverseDNSTable']                                                         = "Reverse DNS";
$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['table']['dns']                                                            = "Reverse DNS";
$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['table']['ip']                                                             = "IP";
$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['table']['ipv']                                                            = "IPV";
$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['reverseDNSEditButton']['button']['reverseDNSEditButton']                  = "Edit Reverse DNS";
$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['deleteReverseDNSButton']['button']['deleteReverseDNSButton']              = "Reset Reverse DNS";

$_LANG['serverCA']['reverseDNS']['reverseDNSEditModal']['modal']['reverseDNSEditModal']                                                         = 'Edit Reverse DNS';
$_LANG['serverCA']['reverseDNS']['reverseDNSEditModal']['reverseDNSEditForm']['dns_ptr']['dns_ptr']                                             = 'Reverse DNS';
$_LANG['serverCA']['reverseDNS']['reverseDNSEditModal']['baseAcceptButton']['title']                                                            = 'Save';
$_LANG['serverCA']['reverseDNS']['reverseDNSEditModal']['baseCancelButton']['title']                                                            = 'Cancel';

$_LANG['serverCA']['reverseDNS']['mainContainer']['reverseDNSTable']['addReverseDNSButton']['button']['addReverseDNSButton']                    = 'Create IPv6 Reverse DNS';
$_LANG['serverCA']['reverseDNS']['addReverseDNSModal']['modal']['addReverseDNSModal']                                                           = 'Create IPv6 Reverse DNS';
$_LANG['serverCA']['reverseDNS']['addReverseDNSModal']['reverseDNSAddForm']['ipv6']['ipv6']                                                     = 'IP';
$_LANG['serverCA']['reverseDNS']['addReverseDNSModal']['reverseDNSAddForm']['dns_ptr']['dns_ptr']                                               = 'Reverse DNS';
$_LANG['serverCA']['reverseDNS']['addReverseDNSModal']['baseAcceptButton']['title']                                                             = 'Create';
$_LANG['serverCA']['reverseDNS']['addReverseDNSModal']['baseCancelButton']['title']                                                             = 'Cancel';
$_LANG['ipAlreadyUsed']                                                                                                                         = 'This IPv6 is already used';

$_LANG['serverCA']['reverseDNS']['deleteReverseDNSModal']['modal']['deleteReverseDNSModal']                                                     = 'Reset Reverse DNS';
$_LANG['serverCA']['reverseDNS']['deleteReverseDNSModal']['deleteReverseDNSForm']['confirmReverseDNSDelete']                                    = 'Are you sure that you want to reset this reverse DNS?';
$_LANG['serverCA']['reverseDNS']['deleteReverseDNSModal']['baseAcceptButton']['title']                                                          = 'Confirm';
$_LANG['serverCA']['reverseDNS']['deleteReverseDNSModal']['baseCancelButton']['title']                                                          = 'Cancel';
$_LANG['resetReverseDNSSuccess']                                                                                                                = 'The reverse DNS has been reset successfully';

$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaReverseDNS]']['packageconfigoption[clientAreaReverseDNS]']       = 'Reverse DNS';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['leftSection']['packageconfigoption[clientAreaReverseDNS]']['clientAreaReverseDNSDescription']       = 'Enable if you want clients to access the "Reverse DNS" section.';




// ---------------------------------------------------- Client Area Console ---------------------------------------------------------
$_LANG['serverCA']['console']['mainContainer']['consolePage']['consolePage'] = "Console";


$_LANG['serverAA']['productPage']['cronInformation'] = "Cron Commands Information";

$_LANG['vmIsEmpty'] = "The server ID is empty.";
$_LANG['vmShouldBeRunning'] = "The virtual machine must be running.";

$_LANG['serverCA']['rebuild']['mainContainer']['alertBox']['newVMPassword'] = "Your new, one time password:";


$_LANG['serverAA']['productPage']['mainContainer']['rebuildTable']['rebuildTable']          = "Rebuild Virtual Machine";
$_LANG['serverAA']['productPage']['mainContainer']['rebuildTable']['table']['id']           = "ID";
$_LANG['serverAA']['productPage']['mainContainer']['rebuildTable']['table']['name']         = "Name";
$_LANG['serverAA']['productPage']['mainContainer']['rebuildTable']['table']['distribution'] = "Distribution";

$_LANG['serverAA']['productPage']['mainContainer']['rebuildTable']['confirmRebuildButton']['button']['confirmButton'] = "Rebuild";

$_LANG['serverAA']['productPage']['confirmRebuildModal']['modal']['confirmRebuildModal']         = 'Rebuild Virtual Machine';
$_LANG['serverAA']['productPage']['confirmRebuildModal']['rebuildConfirmForm']['rebuildConfirm'] = 'Rebuilding your server will power it down and overwrite its disk with the image you select. All previous data on the disk will be lost!<br /><br />Are you sure that you want to rebuild this virtual machine?';
$_LANG['serverAA']['productPage']['confirmRebuildModal']['baseAcceptButton']['title']            = 'Confirm';
$_LANG['serverAA']['productPage']['confirmRebuildModal']['baseCancelButton']['title']            = 'Cancel';
$_LANG['serverAA']['productPage']['mainContainer']['alertBox']['newVMPassword'] = "A new, one time password:";

$_LANG['distribution']['centos'] ="CentOS";
$_LANG['distribution']['debian'] = 'Debian';
$_LANG['distribution']['fedora'] = 'Fedora';
$_LANG['distribution']['ubuntu'] = 'Ubuntu';

$_LANG['template']['Ubuntu 16.04'] = 'Ubuntu 16.04';
$_LANG['template']['Ubuntu 18.04'] = 'Ubuntu 18.04';
$_LANG['template']['Ubuntu 20.04'] = 'Ubuntu 20.04';
$_LANG['template']['CentOS 7'] = 'CentOS 7';
$_LANG['template']['CentOS 8'] =  'CentOS 8';
$_LANG['template']['Debian 9'] = 'Debian 9';
$_LANG['template']['Debian 10'] = 'Debian 10';
$_LANG['template']['Fedora 30'] = 'Fedora 30';
$_LANG['template']['Fedora 32'] = 'Fedora 32';

$_LANG['iso']['CentOS-6.10-x86_64-minimal.iso'] = 'CentOS 6.10 x86 64 Minimal';
$_LANG['iso']['Debian 8.10 (amd64/netinstall)'] = 'Debian 8.10 (amd64/netinstall)';
$_LANG['iso']['Windows Server 2016 English'] = 'Windows Server 2016 English';
$_LANG['iso']['Windows Server 2016 German']= 'Windows Server 2016 German';
$_LANG['iso']['Ubuntu 18.04.4 (amd64)'] = 'Ubuntu 18.04.4 (amd64)';
$_LANG['iso']['AlmaLinux 8.4 (amd64/boot)'] = "AlmaLinux 8.4 (amd64/boot)";
$_LANG['iso']['Alpine Virtual 3.13.1 (amd64)'] = "Alpine Virtual 3.13.1 (amd64)";
$_LANG['iso']['Archlinux 2020.06.01 (amd64)'] = "Archlinux 2020.06.01 (amd64)";
$_LANG['iso']['CentOS 7.9 (amd64/netinstall)'] = "CentOS 7.9 (amd64/netinstall)";
$_LANG['iso']['CentOS 8.2 (amd64/netinstall)'] = "CentOS 8.2 (amd64/netinstall)";
$_LANG['iso']['Clonezilla 2.7.0'] = "Clonezilla 2.7.0";
$_LANG['iso']['Debian 9.13 (amd64/netinstall)'] = "Debian 9.13 (amd64/netinstall)";
$_LANG['iso']['Debian 10.10 (amd64/netinstall)'] = "Debian 10.10 (amd64/netinstall)";
$_LANG['iso']['FreeBSD 11.4 (amd64/netinstall)'] = "FreeBSD 11.4 (amd64/netinstall)";
$_LANG['iso']['FreeBSD 12.1 (amd64/netinstall)'] = "FreeBSD 12.1 (amd64/netinstall)";
$_LANG['iso']['FreeBSD 12.2 (amd64/netinstall)'] = "FreeBSD 12.2 (amd64/netinstall)";
$_LANG['iso']['FreeBSD 13.0 (amd64/netinstall)'] = "FreeBSD 13.0 (amd64/netinstall)";
$_LANG['iso']['FreePBX 2002 (amd64)'] = "FreePBX 2002 (amd64)";
$_LANG['iso']['IPFire 2.23 (amd64)'] = "IPFire 2.23 (amd64)";
$_LANG['iso']['k3OS v0.21.1 (amd64)'] = "k3OS v0.21.1 (amd64)";
$_LANG['iso']['Kali Linux 2020.1b installer (amd64)'] = "Kali Linux 2020.1b installer (amd64)";
$_LANG['iso']['NetBSD 9.1 (amd64)'] = "NetBSD 9.1 (amd64)";
$_LANG['iso']['NixOS 21.05 (amd64/minimal)'] = "NixOS 21.05 (amd64/minimal)";
$_LANG['iso']['OpenBSD 6.9'] = "OpenBSD 6.9";
$_LANG['iso']['openSUSE Leap 15.3'] = "openSUSE Leap 15.3";
$_LANG['iso']['OPNsense 20.7 (amd64)'] = "OPNsense 20.7 (amd64)";
$_LANG['iso']['Oracle Linux 8.4 (amd64/boot)'] = "Oracle Linux 8.4 (amd64/boot)";
$_LANG['iso']['pfSense CE 2.5.0 (amd64)'] = "pfSense CE 2.5.0 (amd64)";
$_LANG['iso']['Proxmox Mail Gateway 6.4 ISO Installer'] = "Proxmox Mail Gateway 6.4 ISO Installer";
$_LANG['iso']['Proxmox Mail Gateway 7.0 ISO Installer'] = "Proxmox Mail Gateway 7.0 ISO Installer";
$_LANG['iso']['Proxmox VE 6.4 ISO Installer'] = "Proxmox VE 6.4 ISO Installer";
$_LANG['iso']['RancherOS 1.5.8'] = "RancherOS 1.5.8";
$_LANG['iso']['Rocky Linux 8.4 (amd64/boot)'] = "Rocky Linux 8.4 (amd64/boot)";
$_LANG['iso']['Securepoint UTM Interactive Installer'] = "Securepoint UTM Interactive Installer";
$_LANG['iso']['Slackware 14.2'] = "Slackware 14.2";
$_LANG['iso']['SystemRescueCD (2018-04-02)'] = "SystemRescueCD (2018-04-02)";
$_LANG['iso']['Ubuntu 20.04 Live Server (amd64)'] = "Ubuntu 20.04 Live Server (amd64)";
$_LANG['iso']['Ubuntu 20.04.1 (amd64)'] = "Ubuntu 20.04.1 (amd64)";
$_LANG['iso']['virtio-win-0.1.185'] = "virtio-win-0.1.185";
$_LANG['iso']['VyOS 1.4 (amd64)'] = "VyOS 1.4 (amd64)";
$_LANG['iso']['Windows Server 2012 R2 English'] = "Windows Server 2012 R2 English";
$_LANG['iso']['Windows Server 2012 R2 German'] = "Windows Server 2012 R2 German";
$_LANG['iso']['Windows Server 2012 R2 Language Pack'] = "Windows Server 2012 R2 Language Pack";
$_LANG['iso']['Windows Server 2012 R2 Russian'] = "Windows Server 2012 R2 Russian";
$_LANG['iso']['Windows Server 2016 Language Pack'] = "Windows Server 2016 Language Pack";
$_LANG['iso']['Windows Server 2016 Russian'] = "Windows Server 2016 Russian";
$_LANG['iso']['Windows Server 2019 English'] = "Windows Server 2019 English";
$_LANG['iso']['Windows Server 2019 German'] = "Windows Server 2019 German";
$_LANG['iso']['Windows Server 2019 Russian'] = "Windows Server 2019 Russian";

$_LANG['serverCA']['iconTitle']['isos'] = "ISO Images";
$_LANG['serverCA']['sidebarMenu']['isos'] ="ISO Images";
$_LANG['serverCA']['isos']['mainContainer']['isosTable']['isosTable']  = "Available ISO Images";
$_LANG['serverCA']['isos']['mainContainer']['isosTable']['table']['description'] = "ISO Image";
$_LANG['mountIso']    = "The process of mounting the :description: ISO Image has started successfully";
$_LANG['serverCA']['isos']['mainContainer']['isosTable']['unmountButton']['button']['unmountButton'] = 'Unmount';
$_LANG['serverCA']['isos']['mainContainer']['isosTable']['mountButton']['button']['mountButton'] = "Mount ISO Image";
$_LANG['serverCA']['isos']['mountModal']['modal']['mountModal']  = "Mount ISO Image";
$_LANG['serverCA']['isos']['mountModal']['mountForm']['mountConfirm'] = "Are you sure that you want to mount the: :description: ISO Image to this virtual machine?";
$_LANG['serverCA']['isos']['mountModal']['baseAcceptButton']['title'] = "Confirm";
$_LANG['serverCA']['isos']['mountModal']['baseCancelButton']['title'] = "Cancel";
$_LANG['serverCA']['isos']['unmountModal']['modal']['unmountModal']   = "Unmount ISO Image";
$_LANG['serverCA']['isos']['unmountModal']['unmountForm']['unmountConfirm'] = "Are you sure that you want to unmount the: :description:  ISO Image from this virtual machine?";
$_LANG['unmountIso']    = "The process of unmounting the :description: ISO Image has been started successfully";
$_LANG['serverCA']['isos']['unmountModal']['baseAcceptButton']['title'] ="Confirm";
$_LANG['serverCA']['isos']['unmountModal']['baseCancelButton']['title'] ="Cancel";

$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaSnapshots]']['packageconfigoption[clientAreaSnapshots]'] ='Snapshots';
$_LANG['serverAA']['productPage']['mainContainer']['featuresForm']['clientAreaFeatures']['rightSection']['packageconfigoption[clientAreaSnapshots]']['clientAreaSnapshotsDescription'] = 'Enable to grant clients the option to create snapshots.';
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[snapshots]']['packageconfigoption[snapshots]'] ='Snapshots  Limit [GB]'  ;
$_LANG['serverAA']['productPage']['mainContainer']['configurationForm']['configFields']['rightSection']['packageconfigoption[snapshots]']['snapshotsDescription'] = 'Specify snapshots size limit here. Set -1 for unlimited.';
$_LANG['serverCA']['iconTitle']['snapshots'] = 'Snapshots';
$_LANG['serverCA']['sidebarMenu']['snapshots'] = 'Snapshots';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['snapshotsTable'] = 'Snapshots';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['table']['description'] = 'Description';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['table']['created']  = 'Created';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['table']['imageSize'] = ' Size';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['table']['status'] = 'Status';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['createButton']['button']['createButton'] = 'Create Snapshot';
$_LANG['serverCA']['snapshots']['createModal']['modal']['createModal'] = 'Create New Snapshot';
$_LANG['serverCA']['snapshots']['createModal']['createForm']['createConfirm']= 'Are you sure that you want to create a new snapshot?<br /><br />';
$_LANG['serverCA']['snapshots']['createModal']['createForm']['description']['description'] = 'Snapshot Description';
$_LANG['serverCA']['snapshots']['createModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['serverCA']['snapshots']['createModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['createSnapshot']= 'The process of creating a new snapshot has started successfully. It may take a few minutes.';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['deleteMassButton']['button']['deleteMassButton']= 'Delete';
$_LANG['serverCA']['snapshots']['deleteMassModal']['modal']['deleteMassModal']= 'Delete Snapshots';
$_LANG['serverCA']['snapshots']['deleteMassModal']['deleteMassForm']['deleteMassConfirm']= 'Are you sure that you want to delete the selected snapshots?';
$_LANG['serverCA']['snapshots']['deleteMassModal']['baseAcceptButton']['title']= 'Confirm';
$_LANG['serverCA']['snapshots']['deleteMassModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['deleteMassSnapshot'] = 'The selected snapshots have been deleted successfully';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['restoreButton']['button']['restoreButton']= 'Restore';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['updateButton']['button']['updateButton']= 'Edit';
$_LANG['serverCA']['snapshots']['mainContainer']['snapshotsTable']['deleteButton']['button']['deleteButton'] = 'Delete';
$_LANG['serverCA']['snapshots']['restoreModal']['modal']['restoreModal'] = 'Restore Snapshot';
$_LANG['serverCA']['snapshots']['restoreModal']['restoreForm']['restoreConfirm'] = ' Are you sure that you want to restore the :description: snapshot? All previous data on the disk will be lost!';
$_LANG['serverCA']['snapshots']['restoreModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['serverCA']['snapshots']['restoreModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['snapshots']['updateModal']['modal']['updateModal'] = 'Edit Snapshot';
$_LANG['serverCA']['snapshots']['updateModal']['updateForm']['description']['description'] = 'Snapshot Description';
$_LANG['serverCA']['snapshots']['updateModal']['baseAcceptButton']['title']  = 'Confirm';
$_LANG['serverCA']['snapshots']['updateModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['snapshots']['deleteModal']['modal']['deleteModal'] = 'Delete Snapshot';
$_LANG['serverCA']['snapshots']['deleteModal']['deleteForm']['deleteConfirm'] = ' Are you sure that you want to delete the :description: snapshot? ';
$_LANG['serverCA']['snapshots']['deleteModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['serverCA']['snapshots']['deleteModal']['baseCancelButton']['title']  = 'Cancel';
$_LANG['updateSnapshot'] = "The :description: snapshot has been updated successfully";
$_LANG['deleteSnapshot'] = "The :description: snapshot has been deleted successfully";
$_LANG['status']['creating'] = 'Creating';
$_LANG['status']['available'] = 'Available';
$_LANG['restoreSnapshot']= 'The process of restoring the :description: snapshot has been started successfully. It may take a few minutes.';
$_LANG['accessDenied'] = 'Access Denied';
$_LANG['snapshotLimitExceded']= 'The maximum size set for a snapshot has been exceeded. Please remove old snapshot files.';
$_LANG['serverCA']['rebuild']['mainContainer']['alertBox']['sshInfo'] = 'In order to log in to the machine, you will need the SSH Key that you provided while placing the order.';
$_LANG['serverAA']['adminServicesTabFields']['mainContainer']['isosTable']['isosTable'] = 'ISO Images' ;
$_LANG['serverAA']['adminServicesTabFields']['mainContainer']['isosTable']['table']['description'] = "Available ISO Images";
$_LANG['mountIso']    = "The process of mounting ISO :description: has started successfully";
$_LANG['serverAA']['adminServicesTabFields']['mainContainer']['isosTable']['unmountButton']['button']['unmountButton'] = 'Unmount';
$_LANG['serverAA']['adminServicesTabFields']['mainContainer']['isosTable']['mountButton']['button']['mountButton'] = "Mount this ISO Image";
$_LANG['serverAA']['adminServicesTabFields']['mountModal']['modal']['mountModal']  = "Mount ISO Image";
$_LANG['serverAA']['adminServicesTabFields']['mountModal']['mountForm']['mountConfirm'] = "Are you sure that you want to mount the: :description: ISO Image to this virtual machine?";
$_LANG['serverAA']['adminServicesTabFields']['mountModal']['baseAcceptButton']['title'] = "Confirm";
$_LANG['serverAA']['adminServicesTabFields']['mountModal']['baseCancelButton']['title'] = "Cancel";
$_LANG['serverAA']['adminServicesTabFields']['unmountModal']['modal']['unmountModal']   = "Unmount ISO Image";
$_LANG['serverAA']['adminServicesTabFields']['unmountModal']['unmountForm']['unmountConfirm'] = "Are you sure that you want to unmount the: :description:  ISO Image from this virtual machine?";
$_LANG['serverAA']['adminServicesTabFields']['unmountModal']['baseAcceptButton']['title'] = "Confirm";
$_LANG['serverAA']['adminServicesTabFields']['unmountModal']['baseCancelButton']['title'] = "Cancel";
$_LANG['errorSnapshotIsCreating'] = "You cannot take another snapshot until the previous one is completed. The server is locked.";
$_LANG['errorSnapshotIsRestoring'] = "You cannot restore the snapshot while another one is being created. The server is locked.";

$_LANG['serverAA']['adminServicesTabFields']['createCOConfirmModal']['createConfigurableAction']['configurableOptionsNameInfo']  = 'Below you can choose which configurable options will be generated for this product. Please note that these options are divided into two parts separated by a | sign, where the part on the left indicates the sent variable and the part on the right the friendly name displayed to customers. After generating these options you can edit the friendly part on the right, but not the variable part on the left. More information about configurable options and their editing can be found :configurableOptionsNameUrl:.';

// ---------------------------------------------------- Client Area Graphs ---------------------------------------------------------
$_LANG['addonCA']['breadcrumbs']['MG Demo']                                                                             = 'addonCA breadcrumbs MG Demo';
$_LANG['serverCA']['iconTitle']['graphs']                                                                               = 'Graphs';
$_LANG['serverCA']['graphs']['Bytes/s']                                                                                 = 'Bytes/s';
$_LANG['serverCA']['graphs']['CPU Use']                                                                                 = 'Percentage (%)';
$_LANG['serverCA']['graphs']['CPU Usage']                                                                               = 'CPU Usage';
$_LANG['serverCA']['graphs']['Disk Usage']                                                                              = 'Bytes/s';
$_LANG['serverCA']['graphs']['Disk Read']                                                                               = 'Read';
$_LANG['serverCA']['graphs']['Disk Write']                                                                              = 'Write';
$_LANG['serverCA']['graphs']['button']['settingButton']                                                                 = 'Edit graph settings';
$_LANG['serverCA']['graphs']['mainContainer']['networkGraph']['title']                                                  = 'Network';
$_LANG['serverCA']['graphs']['mainContainer']['networkGraph']['graphsEditButton']['button']['graphsEditButton']         = 'Edit';
$_LANG['serverCA']['graphs']['mainContainer']['cpuGraph']['cpuGraph']                                                   = 'CPU Usage';
$_LANG['serverCA']['graphs']['mainContainer']['diskGraph']['diskGraph']                                                 = 'Memory Usage';
$_LANG['serverCA']['graphs']['mainContainer']['networkGraph']['networkGraph']                                           = 'Network Traffic';
$_LANG['serverCA']['graphs']['graphEditModal']['modal']['graphEditModal']                                               = 'Edit Settings';
$_LANG['serverCA']['graphs']['graphEditModal']['baseAcceptButton']['title']                                             = 'Save';
$_LANG['serverCA']['graphs']['graphEditModal']['baseCancelButton']['title']                                             = 'Cancel';
$_LANG['Net In']                                                                                                        = 'Net In';
$_LANG['Net Out']                                                                                                       = 'Net Out';

$_LANG['serverCA']['graphs']['Hour']                                                                                    = 'Hour';
$_LANG['serverCA']['graphs']['settingModal']['modal']['settingModal']                                                   = 'Settings';
$_LANG['serverCA']['graphs']['settingModal']['settingForm']['timeframe']['timeframe']                                   = 'Time Frame';
$_LANG['serverCA']['graphs']['settingModal']['baseAcceptButton']['title']                                               = 'Confirm';
$_LANG['serverCA']['graphs']['settingModal']['baseCancelButton']['title']                                               = 'Cancel';

$_LANG['Minute']                                                                                                        = '1 Minute';
$_LANG['15 Minutes']                                                                                                    = '15 Minutes';
$_LANG['1 Hour']                                                                                                        = '1 Hour';
$_LANG['12 Hours']                                                                                                      = '12 Hours';
$_LANG['1 Day']                                                                                                         = '1 Day';
$_LANG['1 Week']                                                                                                        = '1 Week';
$_LANG['1 Month']                                                                                                       = '1 Month';

// -------------------------------------------------- Firewalls --------------------------------------------------

$_LANG['noDataAvalible']                                                                                                = 'No Data Available';
$_LANG['datatableItemsSelected']                                                                                        = 'Items Selected';

$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['firewallTable']                                      = 'Firewalls';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['addFirewallButton']['button']['addFirewallButton']   = 'Create Firewall';

$_LANG['serverCA']['firewalls']['addFirewallModal']['modal']['addFirewallModal']                                        = 'Create Firewall';
$_LANG['serverCA']['firewalls']['addFirewallModal']['baseAcceptButton']['title']                                        = 'Confirm';
$_LANG['serverCA']['firewalls']['addFirewallModal']['baseCancelButton']['title']                                        = 'Cancel';

$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['ip']                                        = 'Ip';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['name']                                      = 'Name';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['direction']                                 = 'Direction';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['protocol']                                  = 'Protocol';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['port']                                      = 'Port';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['created']                                   = 'Created';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['amountIn']                                  = 'Inbound Rules';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['table']['amountOut']                                 = 'Outbound Rules';

$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['table']['protocol']                                      = 'Protocol';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['table']['port']                                          = 'Port';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['table']['ip']                                            = 'Ip';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['table']['direction']                                     = 'Direction';

$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['redirectButton']['button']['redirectButton']                                             = 'Edit';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['editFirewallButton']['button']['editFirewallButton']                                     = 'Edit';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['deleteFirewallButton']['button']['deleteFirewallButton']                                 = 'Delete';

$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['addRuleButton']['button']['addRuleButton']               = 'Create Rule';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['deleteMassRuleButton']['button']['deleteMassRuleButton'] = 'Delete';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['editRuleButton']['button']['editRuleButton']             = 'Edit';
$_LANG['serverCA']['firewalls']['mainContainer']['dataTable']['deleteRuleButton']['button']['deleteRuleButton']         = 'Delete';

$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['mainRawSection']['name']['name'] = 'Firewall Name';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['mainRawSection']['applyTo']['applyTo'] = 'Apply to Server';

$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rowSection']['leftSection']['name']['name']                                         = 'Name';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rowSection']['rightSection']['label']['label']                                      = 'Label';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['rulesLeftSection']['protocol']['protocol']                       = 'Protocol';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['rulesLeftSection']['port']['port']                               = 'Port';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['rulesRightSection']['direction']['direction']                    = 'Direction';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['rulesRightSection']['sourceIp']['sourceIp']                      = 'Source IP';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['rulesRightSection']['destinationIp']['destinationIp']            = 'Destination IP';

$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['port']['port']                                                   = 'Port';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['sourceIp']['sourceIp']                                           = 'Source IP';
$_LANG['serverCA']['firewalls']['addFirewallModal']['firewallAddForm']['rulesRowSection']['destinationIp']['destinationIp']                                 = 'Destination IP';

$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['port']['port']                                     = 'Port';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['sourceIp']['sourceIp']                             = 'Source IP';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['destinationIp']['destinationIp']                   = 'Destination IP';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['rulesRowSection']['rulesLeftSection']['protocol']['protocol']     = 'Protocol';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['rulesRowSection']['rulesRightSection']['direction']['direction']  = 'Direction';

$_LANG['serverCA']['firewalls']['addRuleModal']['modal']['addRuleModal']                                                                                = 'Create Rule';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['rulesRowSection']['rulesLeftSection']['protocol']['protocol']         = 'Protocol';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['rulesRowSection']['rulesRightSection']['direction']['direction']      = 'Direction';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['port']['port']                                         = 'Port';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['port']['portDescription']                              = 'Port or port range to which traffic will be allowed, only applicable for protocols TCP and UDP.<br>A port range can be specified by separating two ports with a dash, e.g <b>1024-5000</b>.';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['sourceIp']['sourceIp']                                 = 'Source IP';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['destinationIp']['destinationIp']                       = 'Destination IP';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['sourceIp']['ipDescription']                            = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';
$_LANG['serverCA']['firewalls']['addRuleModal']['ruleAddForm']['mainRawSection']['baseSection']['destinationIp']['ipDescription']                       = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';
$_LANG['serverCA']['firewalls']['addRuleModal']['baseAcceptButton']['title']                                                                            = 'Confirm';
$_LANG['serverCA']['firewalls']['addRuleModal']['baseCancelButton']['title']                                                                            = 'Cancel';

$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['port']['portDescription']                          = 'Port or port range to which traffic will be allowed, only applicable for protocols TCP and UDP.<br>A port range can be specified by separating two ports with a dash, e.g <b>1024-5000</b>.';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['sourceIp']['ipDescription']                        = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';
$_LANG['serverCA']['firewalls']['addFirewallModal']['ruleAddForm']['mainRawSection']['baseSection']['destinationIp']['ipDescription']                   = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';

$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['rulesRowSection']['rulesLeftSection']['protocol']['protocol']       = 'Protocol';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['rulesRowSection']['rulesRightSection']['direction']['direction']    = 'Direction';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['port']['port']                                   = 'Port';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['sourceIp']['sourceIp']                           = 'Source IP';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['destinationIp']['destinationIp']                 = 'Destination IP';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['port']['portDescription']                        = 'Port or port range to which traffic will be allowed, only applicable for protocols TCP and UDP.<br>A port range can be specified by separating two ports with a dash, e.g <b>1024-5000</b>.';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['sourceIp']['ipDescription']                      = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';
$_LANG['serverCA']['firewalls']['editRuleModal']['editRuleForm']['mainRawSection']['baseSection']['destinationIp']['ipDescription']                 = 'List of permitted IPv4/IPv6 addresses in CIDR notation.<br> Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br> You can specify 100 CIDRs at most.<br>Separate each address with a comma from the others';

$_LANG['serverCA']['firewalls']['editRuleModal']['modal']['editRuleModal']                                          = 'Edit Rule';
$_LANG['serverCA']['firewalls']['editRuleModal']['baseAcceptButton']['title']                                       = 'Confirm';
$_LANG['serverCA']['firewalls']['editRuleModal']['baseCancelButton']['title']                                       = 'Cancel';

$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['deleteFirewallButton']['button']['ButtonModal']                  = 'AAA';
$_LANG['serverCA']['firewalls']['mainContainer']['firewallTable']['deleteMassFirewallButton']['button']['deleteMassFirewallButton'] = 'Delete';
$_LANG['serverCA']['firewalls']['deleteFirewallModal']['modal']['deleteFirewallModal']                                              = 'Delete Firewall';
$_LANG['serverCA']['firewalls']['deleteFirewallModal']['deleteFirewallForm']['confirmFirewallDelete']                               = 'Are you sure that you want to delete this firewall?';
$_LANG['serverCA']['firewalls']['deleteFirewallModal']['deleteMassFirewallForm']['confirmFirewallDelete']                           = 'Are you sure that you want to delete the selected firewalls?';
$_LANG['serverCA']['firewalls']['deleteFirewallModal']['baseAcceptButton']['title']                                                 = 'Delete';
$_LANG['serverCA']['firewalls']['deleteFirewallModal']['baseCancelButton']['title']                                                 = 'Cancel';

$_LANG['serverCA']['firewalls']['deleteRuleModal']['modal']['deleteRuleModal']                                          = 'Delete Rule';
$_LANG['serverCA']['firewalls']['deleteRuleModal']['deleteRuleForm']['confirmRuleDelete']                               = 'Are you sure that you want to delete this firewall rule?';
$_LANG['serverCA']['firewalls']['deleteRuleModal']['deleteMassRuleForm']['confirmFirewallDelete']                       = 'Are you sure that you want to delete the selected firewall rules?';
$_LANG['serverCA']['firewalls']['deleteRuleModal']['baseAcceptButton']['title']                                         = 'Confirm';
$_LANG['serverCA']['firewalls']['deleteRuleModal']['baseCancelButton']['title']                                         = 'Cancel';

$_LANG['serverCA']['firewalls']['deleteMassRuleModal']['modal']['deleteMassRuleModal']                                  = 'Delete Rules';
$_LANG['serverCA']['firewalls']['deleteMassRuleModal']['deleteMassRuleForm']['confirmFirewallDelete']                   = 'Are you sure that you want to delete the selected firewall rules?';
$_LANG['serverCA']['firewalls']['deleteMassRuleModal']['baseAcceptButton']['title']                                     = 'Confirm';
$_LANG['serverCA']['firewalls']['deleteMassRuleModal']['baseCancelButton']['title']                                     = 'Cancel';

$_LANG['serverCA']['firewalls']['deleteMassFirewallModal']['modal']['deleteMassFirewallModal']                          = 'Delete Firewalls';
$_LANG['serverCA']['firewalls']['deleteMassFirewallModal']['deleteMassFirewallForm']['confirmFirewallDelete']           = 'Are you sure that you want to delete the selected firewalls?';
$_LANG['serverCA']['firewalls']['deleteMassFirewallModal']['baseAcceptButton']['title']                                 = 'Delete';
$_LANG['serverCA']['firewalls']['deleteMassFirewallModal']['baseCancelButton']['title']                                 = 'Cancel';

$_LANG['nameAlreadyUsed']                                                                                               = 'This firewall name is already in use';
$_LANG['FirewallCreateSuccessful']                                                                                      = 'The firewall has been created successfully';
$_LANG['FirewallDeleteSuccessful']                                                                                      = 'The firewall has been deleted successfully';
$_LANG['FirewallMassDeleteSuccessful']                                                                                  = 'The firewalls have been deleted successfully';
$_LANG['CreateSuccessful']                                                                                              = 'The rule has been created successfully';
$_LANG['RuleAlreadyExist']                                                                                              = 'The rule you are trying to add already exists';
$_LANG['UpdateSuccessful']                                                                                              = 'The rule has been updated successfully';
$_LANG['DeleteSuccessful']                                                                                              = 'The rule has been deleted successfully';
$_LANG['MassDeleteSuccessful']                                                                                          = 'The rules have been deleted successfully';
$_LANG['invalidIPorMaskSpecified']                                                                                      = 'No or an invalid IP or mask has been specified';
$_LANG['FirewallAndRuleCreatedSuccessfully']                                                                            = 'The firewall rule has been created successfully';
