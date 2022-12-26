<?php

$_LANG['token']                  = ', Error Token:';
$_LANG['generalError']           = 'Something has gone wrong. Check the logs and contact the administrator.';
$_LANG['generalErrorClientArea'] = 'Something has gone wrong. Contact the administrator.';
$_LANG['permissionsStorage']     = ':storage_path: settings are not sufficient. Please set up permissions to the \'storage\' directory as writable.';
$_LANG['permissionsStorageReadable'] = ':storage_path: settings are not sufficient. Please set up permissions to the \'storage\' directory as readable.';
$_LANG['invalidServerType']      = 'The selected server type is invalid, please choose a proper server group with the ":provisioning_name:" servers in order to proceed.';
$_LANG['undefinedAction']        = 'Undefined Action';
$_LANG['ajaxResponses']['undefinedAction'] = 'Undefined Action';
$_LANG['changesHasBeenSaved']    = 'The changes have been saved successfully';
$_LANG['Monthly']                = 'Monthly';
$_LANG['Free Account']           = 'Free Account';
$_LANG['Passwords']              = 'Passwords'; 
$_LANG['Nothing to display']     = 'Nothing to display';
$_LANG['Search']                 = 'Search';
$_LANG['Previous']               = 'Previous';
$_LANG['Next']                   = 'Next';
$_LANG['searchPlacecholder']     = 'Search...';

$_LANG['noDataAvalible']                 = 'No Data Available';
$_LANG['datatableItemsSelected']         = 'Items Selected';
$_LANG['validationErrors']['emptyField'] = 'This field cannot be empty';
$_LANG['bootstrapswitch']['disabled']    = 'Disabled';
$_LANG['bootstrapswitch']['enabled']     = 'Enabled';

$_LANG['ajaxResponses']['changesHasBeenSaved'] = 'The changes have been saved successfully';
$_LANG['ajaxResponses']['configurableOptionsCreated'] = 'The configurable options have been generated successfully';
$_LANG['ajaxResponses']['configurableOptionsUpdated'] = 'The configurable options have been updated successfully';

$_LANG['addonCA']['pageNotFound']['title'] = 'No page has been found';
$_LANG['addonCA']['pageNotFound']['description'] = 'The provided URL does not exist on this page. If you are sure that an error occurred here, please contact support.';
$_LANG['addonCA']['pageNotFound']['button'] = 'Return to the product page';

$_LANG['addonCA']['errorPage']['title'] = 'AN ERROR OCCURRED';
$_LANG['addonCA']['errorPage']['description'] = 'An error occurred. Please contact the administrator and pass the details:';
$_LANG['addonCA']['errorPage']['button'] = 'Return to the product page';
$_LANG['addonCA']['errorPage']['error'] = 'ERROR';

$_LANG['addonCA']['errorPage']['errorCode'] = 'Error Code';
$_LANG['addonCA']['errorPage']['errorToken'] = 'Error Token';
$_LANG['addonCA']['errorPage']['errorTime'] = 'Time';
$_LANG['addonCA']['errorPage']['errorMessage'] = 'Message';

$_LANG['addonAA']['pageNotFound']['title'] = 'PAGE NOT FOUND';
$_LANG['addonAA']['pageNotFound']['description'] = 'An error occurred. Please contact the administrator.';
$_LANG['addonAA']['pageNotFound']['button'] = 'Return to the module page';

$_LANG['addonAA']['errorPage']['title'] = 'AN ERROR OCCURRED';
$_LANG['addonAA']['errorPage']['description'] = 'An error occurred. Please contact the administrator and pass the details:';
$_LANG['addonAA']['errorPage']['button'] = 'Return to the module page';
$_LANG['addonAA']['errorPage']['error'] = 'ERROR';

$_LANG['addonAA']['errorPage']['errorCode'] = 'Error Code';
$_LANG['addonAA']['errorPage']['errorToken'] = 'Error Token';
$_LANG['addonAA']['errorPage']['errorTime'] = 'Time';
$_LANG['addonAA']['errorPage']['errorMessage'] = 'Message';

/* * ********************************************************************************************************************
 *                                                   ERROR CODES                                                        *
 * ******************************************************************************************************************** */
$_LANG['errorCodeMessage']['Uncategorised error occured'] = 'Unexpected Error';
$_LANG['errorCodeMessage']['Database error'] = 'Database Error';
$_LANG['errorCodeMessage']['Provided controller does not exists'] = 'Page Not Found';
$_LANG['errorCodeMessage']['Invalid Error Code!'] = 'Unexpected Error';

/* * ********************************************************************************************************************
 *                                                   MODULE REQUIREMENTS                                                *
 * ******************************************************************************************************************** */
$_LANG['unfulfilledRequirement']['In order for the module to work correctly, please remove the following file: :remove_file_requirement:'] = 'In order for the module to work correctly, please remove the following file: :remove_file_requirement:';
$_LANG['unfulfilledRequirement']['In order for the module to work correctly, it requires the :class_name: class.'] = 'In order for the module to work correctly, it requires the :class_name: class.';
$_LANG['unfulfilledRequirement']['In order for the module to work correctly, it requires the :extension_name: PHP extension to be installed.']= 'In order for the module to work correctly, it requires the :extension_name: PHP extension to be installed.';
/* * ********************************************************************************************************************
 *                                                   ADMIN AREA                                                         *
 * ******************************************************************************************************************** */

$_LANG['addonAA']['datatables']['next']                                                                        = 'Next';
$_LANG['addonAA']['datatables']['previous']                                                                    = 'Previous';
$_LANG['addonAA']['datatables']['zeroRecords']                                                                 = 'Nothing to display';
$_LANG['formValidationError']                                                                                  = 'A form validation error has occurred';
$_LANG['FormValidators']['thisFieldCannotBeEmpty']                                                             = 'This field cannot be empty';
$_LANG['FormValidators']['PleaseProvideANumericValue']                                                         = 'Please provide a numeric value';
$_LANG['FormValidators']['PleaseProvideANumericValueBetween']                                                  = 'Please provide a numeric value between :minValue: and :maxValue:';

$_LANG['changesSaved']                                        = 'The changes have been saved successfully';
$_LANG['ItemNotFound']                                        = 'Item Not Found';
$_LANG['formValidationError']                                 = 'A form validation error has occurred';
$_LANG['FormValidators']['thisFieldCannotBeEmpty']            = 'This field cannot be empty';
$_LANG['FormValidators']['PleaseProvideANumericValue']        = 'Please provide a numeric value';
$_LANG['FormValidators']['PleaseProvideANumericValueBetween'] = 'Please provide a numeric value between :minValue: and :maxValue:';
$_LANG['FormValidators']['notValidBlock']                     = 'The CIDR block you provided is not valid for this IP.';
$_LANG['FormValidators']['wrongIpFormat']                     = 'Please provide an IP in the correct format such as IPv4, CIDR or CIDR block (e.g. 10.10.0.3, 132.13.56.94/32 or ::/32).';
$_LANG['FormValidators']['wrongCidr']                         = 'The provided CIDR is invalid.';
$_LANG['FormValidators']['onlyPortCharacters']                = 'Please provide only numeric values. If you enter a range of ports, separate them by the "-" sign.';


// -------------------------------------------------> MODULE <--------------------------------------------------------- //


$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['regionTitle'] = 'Configuration';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[region]']['region'] = 'Region';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[ami]']['ami'] = 'Amazon Machine Image (AMI)';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[instanceType]']['instanceType'] = 'Instance Type';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[capacityReservationSpecification]']['capacityReservationSpecification'] = 'Capacity Reservation';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[tagName]']['tagName'] = 'Tag Name';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[hibernationOptions]']['hibernationOptions'] = 'Hibernation';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[userData]']['userData'] = 'User Data';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[userDataFile]']['userDataFile'] = 'User Data File';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[securityGroups][]']['securityGroups'] = 'Security Groups';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[ipv4]']['ipv4'] = 'Number of IPv4 Addresses';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[ipv6]']['ipv6'] = 'Number of IPv6 Addresses';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[logApiRequests]']['logApiRequests'] = 'Log API Requests';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[subnet]']['subnet'] = 'Subnet';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[firewall]']['firewall'] = 'Firewall';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[numberOfFirewallRules]']['numberOfFirewallRules'] = 'Number of Firewall Rules';

$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[getWindowsPassword]']['getWindowsPassword'] = 'Show Windows Password';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['caConfigTitle'] = 'Client Area Configuration';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideDnsName]']['hideDnsName'] = 'Hide DNS Name';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideDnsName]']['hideDnsNameDescription'] = 'If enabled, the “Public DNS Name” field will be hidden in the client area.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideScheduledTasks]']['hideScheduledTasks'] = 'Hide Scheduled Tasks';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideScheduledTasks]']['hideScheduledTasksDescription'] = 'If enabled, the “Scheduled Tasks” section will be hidden in the client area.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideIpv6]']['hideIpv6'] = 'Hide IPv6';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[hideIpv6]']['hideIpv6Description'] = 'If enabled, the “IPv6 Addresses” section will be hidden in the client area.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[enableFirewallConfig]']['enableFirewallConfig'] = 'Enable Firewall Configuration';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[enableFirewallConfig]']['enableFirewallConfigDescription'] = 'If enabled and the instance was created without the Firewall option enabled, a client will grant the possibility to modify firewall rules.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['caConfig']['mgpci[getWindowsPassword]']['getWindowsPasswordDescription'] = 'This option will only work for Windows Instances. After the launch of the instance, password generation and encryption may take up to 15 minutes.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[numberOfFirewallRules]']['numberOfFirewallRulesDescription'] = 'Number of firewall rules that a client can set up.';

$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[volumeSize]']['volumeSize'] = 'Volume Size [GB]';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[volumeSize]']['volumeSizeDescription'] = 'The volume size has to be greater than 0. If you do not specify the volume size, the default is the snapshot size. The provisioned IOPS volumes must be at least 100GB in size.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[volumeType]']['volumeType'] = 'Volume Type';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[volumeType]']['volumeTypeDescription'] = 'General Purpose (SSD) volumes can burst to 3000 IOPS, and deliver a consistent baseline of 3 IOPS/GiB. Provisioned IOPS (SSD) volumes can deliver up to 64000 IOPS and are best for EBS-optimized instances. Magnetic volumes (previously called standard volumes) can deliver 100 IOPS on average and burst to hundreds of IOPS.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[iops]']['iops'] = 'Volume IOPS';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[subnet]']['subnetDescription'] = 'The subnet to launch the instance into.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[iops]']['iopsDescription'] = 'This is the requested number of I/O operations per second that the volume can support. For Provisioned IOPS (SSD) volumes, you can provision up to 50 IOPS per GiB, the default is 100 IOPS per GiB and you cannot set less than 100. For General Purpose (SSD) volumes, baseline performance is 3 IOPS per GiB, with a minimum of 100 IOPS and maximum of 16000 IOPS. General Purpose (SSD) volumes under 1000 GiB can burst up to 3000 IOPS. ';

$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[ipv4]']['ipv4Description'] = 'Enter a maximum number of allowed, additional IP addresses. This value will be taken into consideration only if no referring configurable option exists.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[ipv6]']['ipv6Description'] = 'Enter a maximum number of allowed, additional IP addresses. This value will be taken into consideration only if no referring configurable option exists.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[region]']['regionsDescription'] = 'Choose a geographical area with a set of EC2 resources.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[ami]']['amiDescription'] = 'An AMI is a template that contains the software configuration (operating system, application server, and applications) required to launch your instance. You must have previously added any AMIs into the Selected Images table to be able to select any here.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[instanceType]']['instanceTypeDescription'] = 'Select instance type based on the requirements of the application or software that you plan to run on your instance.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[securityGroups][]']['securityGroupsDescription'] = 'A named set of allowed inbound network connections for an instance. Each security group consists of a list of protocols, ports, and IP address ranges. This option works only when the Firewall switch is off.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[capacityReservationSpecification]']['capacityReservationDescription'] = 'Capacity Reservations reserve capacity for your EC2 instances in a specific Availability Zone. You can launch instances into a Capacity Reservation if they have matching attributes (instance type, platform, and Availability Zone), and available capacity.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[hibernationOptions]']['hibernationOptionsDescription'] = 'Enable to allow hibernating your instance. Hibernation stops your instance and saves the contents of its RAM to the root volume.  You cannot enable or disable hibernation after the launch.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[logApiRequests]']['logApiRequestsDescription'] = 'Enable to allow storing information on API requests in the log file.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[tagName]']['tagNameDescription'] = 'Define a tag name. This label will be assigned to EC2 resources.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[userData]']['userDataDescription'] = 'You can specify user data to configure an instance or run a configuration script during the launch. This field is optional.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[userDataFile]']['userDataFileDescription'] = 'You can specify user data file to configure an instance or run a configuration script during the launch. It will be executed before the script from User Data field. This field is optional. User data file is limited to 16 KB. The script has to be located in the /modules/servers/AwsEc2/storage/userDataFiles directory.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['mgpci[firewall]']['firewallDescription'] = 'If enabled before launching the instance, a separate, dedicated security group will be created for this instance during its creation. Otherwise, security groups from the Security Groups field will be attached to that instance.';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[emailTemplate]']['emailTemplate'] = 'Email Template';
$_LANG['serverAA']['productConfig']['mainContainer']['configForm']['region']['hps']['mgpci[emailTemplate]']['emailTemplateDescription'] = 'Choose an email template which will be sent to clients after the instance creation.';
$_LANG['addonAA']['breadcrumbs']['Home'] = '';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['searchName']['searchName'] = 'Name';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['filterImagesTitle'] = 'Images';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImagesTitle'] = 'Search Images';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImagesTitle'] = 'Selected Images';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImages']['table']['description'] = 'Description';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImages']['table']['image_id'] = 'Image ID';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImages']['deleteAmi']['button']['deleteAmiTitle'] = 'Delete Image';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImages']['addAmiManually']['button']['addAmiManuallyTitle'] = 'Add Image Manually';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['selectedImages']['editAmi']['button']['editAmiTitle'] = 'Edit Image';

$_LANG['serverAA']['configOptions']['editImageModal']['modal']['editImageModalTitle'] = 'Edit Image';
$_LANG['serverAA']['configOptions']['editImageModal']['editAmi']['imageIdDisplay']['imageIdDisplay'] = 'Amazon Machine Image (AMI)';
$_LANG['serverAA']['configOptions']['editImageModal']['editAmi']['customImageDescription']['customImageDescription'] = 'Image Description';
$_LANG['serverAA']['configOptions']['editImageModal']['baseAcceptButton']['title'] = 'Save';
$_LANG['serverAA']['configOptions']['editImageModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverAA']['configOptions']['editImageModal']['editAmi']['customImageDescription']['customImageTooltip'] = 'This field cannot be empty. It will be used when creating configurable options.';

$_LANG['serverAA']['configOptions']['addImageManuallyModal']['modal']['addImageManuallyModalTitle'] = 'Add Image Manually';
$_LANG['serverAA']['configOptions']['addImageManuallyModal']['addImageManuallyForm']['imageId']['imageId'] = 'Amazon Machine Image (AMI)';
$_LANG['serverAA']['configOptions']['addImageManuallyModal']['addImageManuallyForm']['customImageDescription']['customImageDescription'] = 'Custom Image Description';
$_LANG['serverAA']['configOptions']['addImageManuallyModal']['addImageManuallyForm']['customImageDescription']['customImageTooltip'] = 'This field is optional. Fill in only if you want to replace the default image description.';
$_LANG['serverAA']['configOptions']['addImageManuallyModal']['baseAcceptButton']['title'] = 'Add';
$_LANG['serverAA']['configOptions']['addImageManuallyModal']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverAA']['configOptions']['deleteImage']['modal']['deleteImageTitle'] = 'Delete Image';
$_LANG['serverAA']['configOptions']['deleteImage']['baseAcceptButton']['title'] = 'Delete';
$_LANG['serverAA']['configOptions']['deleteImage']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverAA']['configOptions']['deleteImage']['deleteAmi']['deleteAmiConfirm'] = 'Are you sure that you want to delete the ":amiId:" image from the list of selected images?';

$_LANG['serverAA']['productConfig']['mainContainer']['cronInfo']['cronInfoTitle'] = 'Cron Commands Information';

$_LANG['serverAA']['productConfig']['mainContainer']['blockedImages']['blockedImagesTitle'] = 'Images';
$_LANG['serverAA']['productConfig']['mainContainer']['blockedImages']['blockedImages_desc']['imagesBlockedInfo'] = 'In order to be able to set up Images, please fill in the Configuration section first (skip the "Amazon Machine Image" field) and press the Save Changes button';


$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['Due to a great number of available images on the AWS platform, please set up as many filters as possible in order the search process was successful'] = 'Due to a great number of available images on the EC2 platform, please set up as many filters as possible in order the search process was successful';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['architecture']['architecture'] = 'Architecture';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['imageType']['imageType'] = 'Image Type';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['platform']['platform'] = 'Platform';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['virtualizationType']['virtualizationType'] = 'Virtualization Type';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['ownerAlias']['ownerAlias'] = 'Owner Alias';

$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['searchName']['descriptionFilterPlaceholder'] = 'Search...';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['name']['descriptionFilter'] = 'Name';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['table']['Description'] = 'Description';
$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['table']['ImageId'] = 'Image ID';

$_LANG['serverAA']['configOptions']['addImageModal']['modal']['addImageModalTitle'] = 'Add To Selected Images';
$_LANG['serverAA']['configOptions']['addImageModal']['baseAcceptButton']['title'] = 'Add';
$_LANG['serverAA']['configOptions']['addImageModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverAA']['configOptions']['addImageModal']['addToAvailableImages']['imageIdDisplay']['imageIdDisplay'] = 'Amazon Machine Image (AMI)';
$_LANG['serverAA']['configOptions']['addImageModal']['addToAvailableImages']['customImageDescription']['customImageDescription'] = 'Custom Image Description';
$_LANG['serverAA']['configOptions']['addImageModal']['addToAvailableImages']['customImageDescription']['customImageTooltip'] = 'This field is optional. Fill in only if you want to replace the default image description.';

$_LANG['serverAA']['productConfig']['mainContainer']['filterImages']['searchImages']['addImageToAvailable']['button']['addImageToAvailableTitle'] = 'Add Image';

$_LANG['serverAA']['productConfig']['mainContainer']['optionsWidget']['optionsWidgetTitle'] = 'Configurable Options';
$_LANG['serverAA']['productConfig']['mainContainer']['optionsWidget']['addOptionsButton']['button']['addOptionButtonsTitle'] = 'Create Configurable Options';

$_LANG['serverAA']['configOptions']['addOptionsModal']['modal']['addOptionsModalTitle'] = 'Create Configurable Options';
$_LANG['serverAA']['configOptions']['addOptionsModal']['baseAcceptButton']['title'] = 'Create';
$_LANG['serverAA']['configOptions']['addOptionsModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverAA']['configOptions']['addOptionsModal']['addOptionsForm']['configurableOptionsNameInfo'] = 'Below you can choose which configurable options will be generated for this product. Please note that these options are divided into two parts separated by a | sign, where the part on the left indicates the sent variable and the part on the right the friendly name displayed to customers. After generating these options you can edit the friendly part on the right, but not the variable part on the left. More information about configurable options and their editing can be found :configurableOptionsNameUrl:.';

$_LANG['serverAA']['configOptions']['doNotUse'] = 'Do not use';

$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['statusWidgetTitle'] = 'Service Actions';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['start']['startTitle'] = 'Start';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['stop']['stopTitle'] = 'Stop';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['reboot']['rebootTitle'] = 'Reboot';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['getKey']['getKeyTitle'] = 'Get SSH Key';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['noDataAvalible'] = 'No Instance Details Available';
$_LANG['serverAA']['adminServicesTabFields']['statusWidget']['InstanceDetailsTitle'] = 'Instance Details';
$_LANG['serverAA']['adminServicesTabFields']['statusWidget']['NetworkInterface'] = 'Network Interface';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['start']['tooltip'] = 'Start the Machine';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['stop']['tooltip'] = 'Stop the Machine';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['reboot']['tooltip'] = 'Reboot the Machine';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['statusWidget']['getKey']['tooltip'] = 'Download the SSH Private Key.';

$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['scheduledTasksTitle'] = 'Scheduled Tasks';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['job'] = 'Type';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['status'] = 'Status';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['message'] = 'Message';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['created_at'] = 'Created';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['retry_count'] = 'Attempts';
$_LANG['serverAA']['servicePageIntegration']['mainContainer']['scheduledTasks']['table']['updated_at'] = 'Updated';

$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['InstanceStatus'] = 'Status';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['ImageId'] = 'Image ID';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['InstanceId'] = 'Instance ID';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['InstanceType'] = 'Instance Type';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['AvailabilityZone'] = 'Availability Zone';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['VirtualizationType'] = 'Virtualization Type';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['CoreCount'] = 'Core Count';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['ThreadsPerCore'] = 'Threads Per Core';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['PublicIp'] = 'Public IP Address';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['MacAddress'] = 'MAC Address';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['Ipv6Addresses'] = 'IPv6 Addresses';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['PrivateIpAddresses'] = 'Private IP Address';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['tags'] = 'Tags';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['PublicDnsName'] = 'Public DNS Name';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['NetworkInterfaceId'] = 'Network Interface ID';
$_LANG['serverAA']['adminServicesTabFields']['statusTitle']['SecurityGroupId'] = 'Security Groups IDs';

$_LANG['serverAA']['adminServicesTabFields']['stopInstance']['modal']['stopInstanceTitle'] = 'Stop Instance';
$_LANG['serverAA']['adminServicesTabFields']['stopInstance']['stopForm']['confirmStopInstance'] = 'Are you sure that you want to stop this instance?';
$_LANG['serverAA']['adminServicesTabFields']['stopInstance']['baseAcceptButton']['title'] = 'Stop';
$_LANG['serverAA']['adminServicesTabFields']['stopInstance']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['rebootInstance']['modal']['rebootInstanceTitle'] = 'Reboot Instance';
$_LANG['serverAA']['adminServicesTabFields']['rebootInstance']['rebootForm']['confirmRebootInstance'] = 'Are you sure that you want to reboot this instance?';
$_LANG['serverAA']['adminServicesTabFields']['rebootInstance']['baseAcceptButton']['title'] = 'Reboot';
$_LANG['serverAA']['adminServicesTabFields']['rebootInstance']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverAA']['adminServicesTabFields']['startInstance']['modal']['startInstanceTitle'] = 'Start Instance';
$_LANG['serverAA']['adminServicesTabFields']['startInstance']['startForm']['confirmStartInstance'] = 'Are you sure that you want to start this instance?';
$_LANG['serverAA']['adminServicesTabFields']['startInstance']['baseAcceptButton']['title'] = 'Start';
$_LANG['serverAA']['adminServicesTabFields']['startInstance']['baseCancelButton']['title'] = 'Cancel';


$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['download']['button']['download'] = 'Download';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['modal']['getKeyModalTitle'] = 'SSH Keys';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['getKeyForm']['confirmGetKeyModal'] = 'Are you sure that you want to get the SSH key for this instance?';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['baseAcceptButton']['title'] = 'Get SSH Key';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['decodeForm']['getSshKeyInfo'] = 'You can download the private SSH key only once. After you do this, it will not be available anymore.';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['decodeForm']['public_key']['public_key'] = 'Public Key';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['decodeForm']['private_key']['private_key'] = 'Private Key';

$_LANG['serverAA']['createAccount']['invalidId'] = 'Selected image cannot be used in selected region. Please try another image.';

/* * ********************************************************************************************************************
 *                                                    CLIENT AREA                                                      *
 * ******************************************************************************************************************** */

$_LANG['serverCA']['home']['mainContainer']['statusWidget']['statusWidgetTitle'] = 'Service Actions';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['statusTitle'] = 'Service Status:';

$_LANG['sidebarMenu']['mg-provisioning-module']                                  = 'Manage Account';
$_LANG['sidebarMenu']['firewallRules']                                           = 'Firewall Rules';


$_LANG['addonCA']['breadcrumbs']['MG Demo'] = '';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['start']['startTitle'] = 'Start';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['stop']['stopTitle'] = 'Stop';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['reboot']['rebootTitle'] = 'Reboot';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['windowsPassword']['windowsPasswordTitle'] = 'Get Windows Password';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['getKey']['getKeyTitle'] = 'Get SSH Key';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['injectSshKey']['injectSshKeyTitle'] = 'Inject SSH Key';

$_LANG['serverCA']['home']['mainContainer']['statusWidget']['start']['tooltip'] = 'Start the Machine';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['stop']['tooltip'] = 'Stop the Machine';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['reboot']['tooltip'] = 'Reboot the Machine';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['windowsPassword']['tooltip'] = 'Get Windows Password';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['getKey']['tooltip'] = 'Download SSH Key';
$_LANG['serverCA']['home']['mainContainer']['statusWidget']['injectSshKey']['tooltip'] = 'Inject your SSH public key into the machine.';



$_LANG['serverCA']['home']['statusWidget']['InstanceDetailsTitle'] = 'Instance Details';
$_LANG['serverCA']['home']['statusWidget']['NetworkInterface'] = 'Network Interface';

$_LANG['serverCA']['home']['statusTitle']['CoreCount'] = 'Core Count';
$_LANG['serverCA']['home']['statusTitle']['ThreadsPerCore'] = 'Threads Per Core';
$_LANG['serverCA']['home']['statusTitle']['PublicIp'] = 'Public IP Address';
$_LANG['serverCA']['home']['statusTitle']['MacAddress'] = 'MAC Address';
$_LANG['serverCA']['home']['statusTitle']['Ipv6Addresses'] = 'IPv6 Addresses';
$_LANG['serverCA']['home']['statusTitle']['InstanceStatus'] = 'Status';
$_LANG['serverCA']['home']['statusTitle']['PublicDnsName'] = 'Public DNS Name';

$_LANG['serverCA']['home']['mainContainer']['statusWidget']['noDataAvalible'] = 'No Instance Details Available';

$_LANG['serverCA']['home']['windowsPasswordDecode']['modal']['windowsPasswordDecodeTitle'] = 'Windows Password';
$_LANG['serverCA']['home']['windowsPasswordDecode']['decodeForm']['getWindowsPasswordInfo'] = 'Please provide a private key, matching the public key used during the instance order process. This method will work only for the autogenerated Windows password.';
$_LANG['serverCA']['home']['windowsPasswordDecode']['decodeForm']['privateKey']['privateKey'] = 'Private Key';
$_LANG['serverCA']['home']['windowsPasswordDecode']['decodeForm']['password']['passwordLabel'] = 'Password';
$_LANG['serverCA']['home']['windowsPasswordDecode']['baseAcceptButton']['title'] = 'Get Password';
$_LANG['serverCA']['home']['windowsPasswordDecode']['baseCancelButton']['title'] = 'Cancel';


$_LANG['serverCA']['home']['stopInstance']['modal']['stopInstanceTitle'] = 'Stop Instance';
$_LANG['serverCA']['home']['stopInstance']['baseAcceptButton']['title'] = 'Stop';
$_LANG['serverCA']['home']['stopInstance']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['home']['stopInstance']['stopForm']['confirmStopInstance'] = 'Are you sure that you want to stop this instance?';

$_LANG['serverCA']['home']['startInstance']['modal']['startInstanceTitle'] = 'Start Instance';
$_LANG['serverCA']['home']['startInstance']['startForm']['confirmStartInstance'] = 'Are you sure that you want to start this instance?';
$_LANG['serverCA']['home']['startInstance']['baseAcceptButton']['title'] = 'Start';
$_LANG['serverCA']['home']['startInstance']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverCA']['home']['rebootInstance']['modal']['rebootInstanceTitle'] = 'Reboot Instance';
$_LANG['serverCA']['home']['rebootInstance']['rebootForm']['confirmRebootInstance'] = 'Are you sure that you want to reboot this instance?';
$_LANG['serverCA']['home']['rebootInstance']['baseAcceptButton']['title'] = 'Reboot';
$_LANG['serverCA']['home']['rebootInstance']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverCA']['home']['getKeyModal']['download']['button']['download'] = 'Download';
$_LANG['serverCA']['home']['getKeyModal']['modal']['getKeyModalTitle'] = 'SSH Keys';
$_LANG['serverCA']['home']['getKeyModal']['baseAcceptButton']['title'] = 'Download Private Key';
$_LANG['serverCA']['home']['getKeyModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['home']['getKeyModal']['getKeyForm']['getSshKeyInfo'] = 'You can download the private SSH key only once. After you do this, it will not be available anymore.';
$_LANG['serverCA']['home']['getKeyModal']['getKeyForm']['public_key']['public_key'] = 'Public Key';
$_LANG['serverCA']['home']['getKeyModal']['getKeyForm']['private_key']['private_key'] = 'Private Key';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['getKeyForm']['getSshKeyInfo'] = 'You can download the private SSH key only once. After you do this, it will not be available anymore.';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['getKeyForm']['public_key']['public_key'] = 'Public Key';
$_LANG['serverAA']['adminServicesTabFields']['getKeyModal']['getKeyForm']['private_key']['private_key'] = 'Private Key';

$_LANG['serverCA']['home']['injectSshKeyModal']['modal']['injectSshKeyModalTitle'] = 'Inject SSH Key';
$_LANG['serverCA']['home']['injectSshKeyModal']['baseAcceptButton']['title'] = 'Inject';
$_LANG['serverCA']['home']['injectSshKeyModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['home']['injectSshKeyModal']['baseForm']['publicKey']['publicKey'] = 'Public Key';
$_LANG['serverCA']['home']['injectSshKeyModal']['baseForm']['username']['username'] = 'Username';

$_LANG['serverCA']['home']['mainContainer']['scheduledTasks']['scheduledTasksTitle'] = 'Scheduled Tasks';
$_LANG['serverCA']['home']['mainContainer']['scheduledTasks']['table']['job'] = 'Type';
$_LANG['serverCA']['home']['mainContainer']['scheduledTasks']['table']['status'] = 'Status';
$_LANG['serverCA']['home']['mainContainer']['scheduledTasks']['table']['created_at'] = 'Created';
$_LANG['serverCA']['home']['mainContainer']['scheduledTasks']['table']['message'] = 'Message';

$_LANG['serverCA']['home']['scheduledTasks']['task']['Creating VM'] = 'Creating VM';
$_LANG['serverCA']['home']['scheduledTasks']['task']['Deleting VM'] = 'Deleting VM';
$_LANG['serverCA']['home']['scheduledTasks']['task']['Changing Package'] = 'Changing Package';
$_LANG['serverCA']['home']['scheduledTasks']['task']['Detaching Network Interface'] = 'Detaching Network Interface';
$_LANG['serverCA']['home']['scheduledTasks']['task']['Loading Windows Password'] = 'Loading Windows Password';
$_LANG['serverCA']['home']['scheduledTasks']['taskStatus']['Finished'] = 'Finished';
$_LANG['serverCA']['home']['scheduledTasks']['taskStatus']['Waiting'] = 'Waiting';
$_LANG['serverCA']['home']['scheduledTasks']['taskStatus']['Running'] = 'Running';
$_LANG['serverCA']['home']['scheduledTasks']['taskStatus']['Error'] = 'Error';

$_LANG['serverCA']['home']['scheduledTasks']['The machine status must be "Stopped" to run this task'] = 'The machine status must be "Stopped" to run this task';

//---------------------------------- Firewall Rules ----------------------------------
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['table']['rule'] = 'Rule';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['table']['protocol'] = 'Protocol';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['table']['ports'] = 'Ports';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['table']['source'] = 'Source';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['editButton']['button']['editButton'] = 'Edit';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['deleteButton']['button']['deleteButton'] = 'Delete';
$_LANG['serverCA']['firewallRules']['mainContainer']['moduleDescription']['description'] = 'In this section you may easily add and remove firewall rules. If the "Add" button is unavailable, you have probably reached the rule limit set by the administrator.';
$_LANG['serverCA']['firewallRules']['mainContainer']['dataTable']['addButton']['button']['addButton'] = 'Add';


$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['firewallRulePageTitle']               = 'Firewall Rules';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['addButton']['button']['addButton']    = 'Add';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['table']['rule']                       = 'Rule';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['table']['protocol']                   = 'Protocol';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['table']['ports']                      = 'Ports';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['table']['source']                     = 'Source';
$_LANG['serverCA']['firewallRules']['mainContainer']['firewallRulePage']['deleteButton']['button']['deleteButton'] = 'Delete';

$_LANG['serverCA']['firewallRules']['deleteModal']['modal']['deleteModal'] = 'Delete Security Group Rule';
$_LANG['serverCA']['firewallRules']['deleteModal']['baseAcceptButton']['title'] = 'Delete';
$_LANG['serverCA']['firewallRules']['deleteModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['serverCA']['firewallRules']['deleteModal']['deleteForm']['confirmDeleteRule'] = 'Are you sure that you want to delete the selected security group rule?';

$_LANG['serverCA']['firewallRules']['addModal']['modal']['addModal'] = 'Add New Security Group Rule';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['type']['type'] = 'Type';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['protocol']['protocol'] = 'Protocol';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['direction']['direction'] = 'Direction';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['portRange']['portRange'] = 'Port Range';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['source']['source'] = 'Source';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['rule']['rule'] = 'Rule';
$_LANG['serverCA']['firewallRules']['addModal']['baseAcceptButton']['title'] = 'Add';
$_LANG['serverCA']['firewallRules']['addModal']['baseCancelButton']['title'] = 'Cancel';

$_LANG['serverCA']['firewallRules']['addModal']['addForm']['type']['typeDescription'] = 'You can select one of the available application types or set custom parameters';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['rule']['ruleDescription'] = 'Select if the security group rule should concern incoming or outgoing connections';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['protocol']['protocolDescription'] = 'Select one of the available protocols';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['portRange']['portRangeDescription'] = 'Type port or port range here. eg. "22" or "234-239" (without quotes). Type "-1" or "All" if you want to choose all ports. For ICMP protocol please input type and code in format Type-Code (e.g. for type 8 and code -1: "8--1")';
$_LANG['serverCA']['firewallRules']['addModal']['addForm']['source']['sourceDescription'] = 'Determines the traffic that can reach your instance. Specify a single IP address, or an IP address range in CIDR notation (for example, 203.0.113.5/32). If connecting from behind a firewall, you will need the IP address range used by the client computers.';

$_LANG['serverCA']['firewallRules']['numberOfRulesExceedTheLimit'] = 'The number of firewall rules exceed the limit. Please try removing some rules first then try again.';
$_LANG['serverCA']['home']['mainContainer']['dataTable']['table']['ipv6'] = 'IPv6';



$_LANG['ajaxResponses']['ruleDeletedSuccesfully'] = 'The firewall rule has been deleted successfully.';
$_LANG['ajaxResponses']['ruleCreatedSuccesfully'] = 'The firewall rule has been added successfully.';
$_LANG['ajaxResponses']['ServiceStopped'] = 'Stopping Service. It may take a while to complete this process.';
$_LANG['ajaxResponses']['ServiceStarted'] = 'Starting Service. It may take a while to complete this process.';
$_LANG['ajaxResponses']['ServiceRebooted'] = 'Rebooting Service. It may take a while to complete this process.';
$_LANG['ajaxResponses']['unableToDecodePassword'] = 'Unable to decode the password';
$_LANG['ajaxResponses']['emptyPublicKeyError']    = 'Please provide the valid SSH public key';
$_LANG['ajaxResponses']['instanceNotStoppedError']    = 'Please stop your instance before injecting the SSH public key';
$_LANG['ajaxResponses']['publicKeyValidationError']    = 'The SSH public key format is wrong. The key should have the OpenSSH format (e.g. ssh-rsa)';
$_LANG['ajaxResponses']['keyInjectionSuccess']    = 'Your SSH key has been injected successfully. You can now start your machine';
$_LANG['ajaxResponses']['imageAlreadyExists'] = 'The image already exists';
$_LANG['ajaxResponses']['descriptionAlreadyExists'] = 'Such a description is already used in another image of this product';
$_LANG['ajaxResponses']['passwordDecodedSuccessfully'] = 'The password has been decoded successfully.';


$_LANG['sidebarMenu']['elasticIps']="Elastic IPs";
$_LANG['sidebarMenu']['networkInterfaces']="Network Interfaces";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['moduleDescription']['description']="";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['networkInterfacesTitle']="Network interfaces";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['name'] ="Name";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['interfaceid']  ="Network interface ID";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['subnet']  ="Subnet ID";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['zone']  ="Availability Zone";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['securitygroup']  ="Security groups";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['interfacetype']  ="Interface Type";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['status']  ="Status";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['publicip']  ="Public IPv4 address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['table']['privateip']  ="Primary private IPv4 address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['addButton']['button']['addButton']="Create network interface";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['disassociateButton']['button']['disassociateButton']="Disassociate  address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['associateButton']['button']['associateButton']="Associate address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['deleteButton']['button']['deleteButton']="Delete";


$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['addForm']="Create network interface
";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['description']['description']="Description - optional";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['description']['descriptionDescription']="A descriptive name for the network interface.";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['subnet']['subnet']="Subnet";

$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['subnet']['subnethelptext']="The subnet in which to create the network interface.";

$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['privateIpAddress']['privateIpAddress']="Private IPv4 address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['privateIpAddress']['descriptionDescription']="The private IPv4 address to assign to the network interface.";
$_LANG['serverCA']['networkInterfaces']['autoAssign']="Auto-assign";
$_LANG['serverCA']['networkInterfaces']['custom']="Custom";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['autoAssign[autoAssign]']['autoAssign']="Auto-assign";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['custom[custom]']['custom']="Custom";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['ipv4address']['ipv4address']="IPv4 address
";

$_LANG['serverCA']['networkInterfaces']['mainContainer']['addForm']['baseSubmitButton']['button']['submit']="Create network interface";
$_LANG['serverCA']['networkInterfaces']['subnetrequired']="Select a subnet";
$_LANG['serverCA']['networkInterfaces']['ipaddressrequiredwithcustom']="Invalid IPv4 address";
$_LANG['serverCA']['networkInterfaces']['networkInterfaceCreatedSuccesfully']="Network interface added successfully";

$_LANG['serverCA']['networkInterfaces']['disassociateModal']['modal']['disassociateModal']="Disassociate public IP address";

$_LANG['serverCA']['networkInterfaces']['deleteModal']['modal']['deleteModal']="Delete network interface";
$_LANG['serverCA']['networkInterfaces']['deleteModal']['deleteForm']['confirmDeleteNetworkInterface']="Are you sure that you want to delete the following network interface?";
$_LANG['serverCA']['networkInterfaces']['deleteModal']['deleteForm']['interfaceid']['interfaceid']="";

$_LANG['serverCA']['networkInterfaces']['deleteModal']['baseAcceptButton']['title']="Delete";

$_LANG['serverCA']['networkInterfaces']['deleteModal']['baseCancelButton']['title']="Cancel";
$_LANG['ajaxResponses']['networkinterfaceDeletedSuccesfully']="Network interface deleted successfully";
$_LANG['serverCA']['elasticIps']['mainContainer']['moduleDescription']['description']="";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['elasticIpsTitle']="Elastic IP addresses";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['table']['name']  ="Name";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['table']['ipv4address']  ="Allocated IPv4 address";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['table']['allocationid']  ="Allocation ID";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['table']['privateip']  ="Private IP address";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['table']['networkinterface']  ="Network Interface";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['addButton']['button']['addElasticIp']="Allocate Elastic IP Address";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['associateButton']['button']['associateButton']="Associate address";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['disassociateButton']['button']['disassociateButton']="Disassociate address";
$_LANG['serverCA']['elasticIps']['mainContainer']['elasticIpsPage']['deleteButton']['button']['deleteButton']="Delete";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['associateIpForm']="Associate Elastic IP address";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['resourceType']['resourceType']="Resource type";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['resourceType']['descriptionDescription']="Choose the type of resource with which to associate the Elastic IP address.";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['networkinterface']['networkinterface']="Network interface";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['privateip']['privateip']="Private IP address
";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['privateip']['privateiphelp']="The private IP address with which to associate the Elastic IP address.";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['reassociate[reassociate]']['reassociate']="Reassociation";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['reassociate[reassociate]']['reassociateSmall']="Specify whether the Elastic IP address can be reassociated with a different resource if it already associated with a resource.";
$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['reassociate[reassociate]']['reassociate']="Allow this Elastic IP address to be reassociated";

$_LANG['serverCA']['elasticIps']['mainContainer']['associateIpForm']['baseSubmitButton']['button']['submit']="Associate";
$_LANG['serverCA']['elasticIps']['ipAssociatedSuccesfully']="Elastic IP associated successfully";
$_LANG['serverCA']['elasticIps']['disassociateModal']['modal']['disassociateModal']="Dissociate Elastic IP address";
$_LANG['serverCA']['elasticIps']['disassociateModal']['deleteForm']['confirmDisassociate']="If you disassociate this Elastic IP address, you can reassociate it with a different resource. The Elastic IP address remains allocated to your account. You can have one Elastic IP (EIP) address associated with a running instance at no charge. If you associate additional EIPs with that instance, you will be charged for each additional EIP associated with that instance on a pro rata basis. Additional EIPs are only available in Amazon VPC. To ensure efficient use of Elastic IP addresses, we impose a small hourly charge when these IP addresses are not associated with a running instance or when they are associated with a stopped instance or unattached network interface.
";
$_LANG['serverCA']['elasticIps']['disassociateModal']['deleteForm']['ipv4address']['ipv4address']="Elastic IP address";
$_LANG['serverCA']['elasticIps']['disassociateModal']['deleteForm']['instanceid']['instanceid']="Instance ID
";
$_LANG['serverCA']['elasticIps']['disassociateModal']['deleteForm']['networkinterface']['networkinterface']="Network interface ID";

$_LANG['serverCA']['elasticIps']['disassociateModal']['baseAcceptButton']['title']="Disassociate";

$_LANG['serverCA']['elasticIps']['disassociateModal']['baseCancelButton']['title']="Cancel";
$_LANG['serverCA']['elasticIps']['deleteModal']['modal']['deleteModal']="Release Elastic IP addresses";
$_LANG['serverCA']['elasticIps']['deleteModal']['deleteForm']['confirmDeleteNetworkInterface']="If you release the following Elastic IP addresses, they will no longer be allocated to your account and you can no longer associate them with your resources.";
$_LANG['serverCA']['elasticIps']['deleteModal']['deleteForm']['interfaceid']['interfaceid']="Elastic IP address";

$_LANG['serverCA']['elasticIps']['deleteModal']['baseAcceptButton']['title']="Release";

$_LANG['serverCA']['elasticIps']['deleteModal']['baseCancelButton']['title']="Cancel";

$_LANG['breadcrumb-dashboard']="Dashboard";
$_LANG['serverCA']['elasticIps']['mainContainer']['breadcrumb']['index']['breadcrumb']="Elastic IPs";
$_LANG['serverCA']['elasticIps']['mainContainer']['breadcrumb']['associate']['breadcrumb']="Associate";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['breadcrumb']['index']['breadcrumb']="Network Interfaces";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['breadcrumb']['add']['breadcrumb']="Create network interface";
$_LANG['serverCA']['elasticIps']['networkinterfacerequired']="Network interface is required";

$_LANG['ajaxResponses']['networkInterfaceCreatedSuccesfully']="Network interface added successfully";

$_LANG['serverCA']['networkInterfaces']['mainContainer']['breadcrumb']['associate']['breadcrumb']="Associate Elastic IP address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['associateIpForm']="Associate Elastic IP address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['networkinterface']['networkinterface']="Network interface"
;
$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['elasticip']['elasticip']="Elastic IP address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['privateip']['privateip']="Private IPv4 address";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['privateip']['privateiphelp']="";

$_LANG['serverCA']['networkInterfaces']['mainContainer']['associateIpForm']['baseSubmitButton']['button']['submit']="Associate";
$_LANG['serverCA']['networkInterfaces']['networkinterfacerequired']="Failed to associate address public IP must be specified";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['attachButton']['button']['attachButton']="Attach";
$_LANG['serverCA']['networkInterfaces']['mainContainer']['networkInterfacesPage']['detachButton']['button']['detachButton']="Detach";

$_LANG['serverCA']['networkInterfaces']['disassociateModal']['deleteForm']['confirmDisassociate']="Are you sure you want to disassociate public ip address";
$_LANG['serverCA']['networkInterfaces']['disassociateModal']['deleteForm']['publicip']['publicip']="Public IP address";
$_LANG['serverCA']['networkInterfaces']['disassociateModal']['deleteForm']['instanceid']['instanceid']="Instance Id";
$_LANG['serverCA']['networkInterfaces']['disassociateModal']['deleteForm']['interfaceid']['interfaceid']="Network interface";

$_LANG['serverCA']['networkInterfaces']['disassociateModal']['baseAcceptButton']['title']="Disassociate";

$_LANG['serverCA']['networkInterfaces']['disassociateModal']['baseCancelButton']['title']="Cancel";

$_LANG['ajaxResponses']['ipDisassociatedSuccesfully']="IP Address disassociated successfully";
$_LANG['ajaxResponses']['ipAssociatedSuccesfully']="IP Address associated successfully";
$_LANG['serverCA']['firewallRules']['mainContainer']['breadcrumb']['index']['breadcrumb']="Firewall Rules";
$_LANG['serverCA']['networkInterfaces']['attachModal']['modal']['attachModal']="Attach network interface";
$_LANG['serverCA']['networkInterfaces']['attachModal']['attachForm']['interfaceid']['interfaceid']="Network interface";

$_LANG['serverCA']['networkInterfaces']['attachModal']['baseAcceptButton']['title']="Attach";

$_LANG['serverCA']['networkInterfaces']['attachModal']['baseCancelButton']['title']="Cancel";
$_LANG['serverCA']['networkInterfaces']['attachModal']['attachForm']['infoAttachNetworkInterfaceToInstance']='This will attach the interface as your secondary network interface to your current instance';
