<?php


$_LANG['token']                  = ', Token Error:';
$_LANG['generalError']           = 'An unexpected error occurred. Check the logs and contact the support team.';
$_LANG['generalErrorClientArea'] = 'An unexpected error occurred. Contact the administrator.';
$_LANG['permissionsStorage']     = ':storage_path: settings are not sufficient. Please change permissions to writable.';
$_LANG['undefinedAction']        = 'Undefined action';
$_LANG['changesHasBeenSaved']    = 'The changes have been saved successfully';
$_LANG['Monthly']                = 'Monthly';
$_LANG['Free Account']           = 'Free Account';
$_LANG['Passwords']              = 'Passwords';
$_LANG['labelAddedSuccesfully']  = 'The label has been added successfully';
$_LANG['Nothing to display']     = 'Nothing to display';
$_LANG['Search']                 = 'Search';
$_LANG['Previous']               = 'Previous';
$_LANG['Next']                   = 'Next';
$_LANG['searchPlacecholder']     = 'Search...';

$_LANG['FormValidators']['thisFieldCannotBeEmpty']            = 'This field cannot be empty';
$_LANG['FormValidators']['PleaseProvideANumericValueBetween'] = 'Please provide a number between 0 and 999';
$_LANG['FormValidators']['invalidDomain']                     = 'The domain is invalid';

$_LANG['noDataAvalible']                 = 'No Data Available';
$_LANG['datatableItemsSelected']         = 'Items Selected';
$_LANG['validationErrors']['emptyField'] = 'This field is required';
$_LANG['bootstrapswitch']['disabled']    = 'Disabled';
$_LANG['bootstrapswitch']['enabled']     = 'Enabled';

$_LANG['addonCA']['pageNotFound']['title'] = 'PAGE NOT FOUND';
$_LANG['addonCA']['pageNotFound']['description'] = 'This page does not exist on the provided URL. If you are sure that an error occurred here, please contact support.';
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
$_LANG['errorCodeMessage']['Database error'] = 'Database error';
$_LANG['errorCodeMessage']['Provided controller does not exists'] = 'Page not found';
$_LANG['errorCodeMessage']['Invalid Error Code!'] = 'Unexpected Error';

/* * ********************************************************************************************************************
 *                                                   MODULE REQUIREMENTS                                                *
 * ******************************************************************************************************************** */
$_LANG['unfulfilledRequirement']['In order for the module to work correctly, please set up permissions to the :writable_file_requirement: directory as writable.'] = 'In order for the module to work correctly, please set up permissions to the :writable_file_requirement: directory as writable.';

/* * ********************************************************************************************************************
 *                                                   ADMIN AREA                                                        *
 * ******************************************************************************************************************** */





$_LANG['addonAA']['breadcrumbs']['VPS'] = 'VPS';
$_LANG['addonAA']['home']['dedicated'] = 'Dedicated';
$_LANG['addonAA']['home']['vps'] = 'VPS';
$_LANG['addonAA']['breadcrumbs']['home'] = 'Servers';
$_LANG['addonAA']['pagesLabels']['label']['home'] = 'Servers';
$_LANG['addonAA']['pagesLabels']['label']['LoggerManager'] = 'Logs';
$_LANG['addonAA']['pagesLabels']['label']['documentation'] = 'Documentation';
$_LANG['addonAA']['pagesLabels']['label']['Home'] = 'Servers';
$_LANG['addonAA']['pagesLabels']['Home']['Dedicated'] = 'Dedicated';
$_LANG['addonAA']['breadcrumbs']['Home'] = 'Servers';


/** SERVERS */
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['id'] = 'ID';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['name'] = 'Name';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['ovhServerType'] = 'OVH Server Type';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['vpsListButton']['button']['vpsListButton'] = 'List of Deployed Machines';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['whmcsServer']['button']['whmcsServer'] = 'Edit Server Configuration';
$_LANG['ovh']['server']['type']['vps']       = 'VPS';
$_LANG['ovh']['server']['type']['dedicated'] = 'Dedicated';
$_LANG['addonAA']['home']['machine']['reuse']['status']['success'] = 'The machine reusability status has been changed successfully';
$_LANG['machine']['button']['reuse']['info']  = 'Toggle on to allow this machine to be reused by a new client without having to terminate it if the previous client has decided not to continue using the service. Otherwise, it might be terminated.';

$_LANG['addonAA']['home']['assignProductsModal']['assignProductsForm']['assignProductsAlertInfo'] = 'Select the products that will be permitted to use this machine again in case it is intended for reusage.';
$_LANG['addonAA']['home']['assignClientModal']['assignClientForm']['assignClientAlertInfo'] = 'Select the client and their existing service that you would like to associate with the chosen machine.';


/*** DEDIACTED *********/
$_LANG['addonAA']['breadcrumbs']['Dedicated'] = 'Dedicated';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['name'] = 'Name';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['state'] = 'State';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['ip'] = 'IP Address';
$_LANG['addonAA']['home']['assignClientModal']['assignClientForm']['clientRemoteSearch']['clientRemoteSearch'] = 'Client';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['service'] = 'Service';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['reuse'] = 'Reusable ';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['assignClient']['button']['assignClient'] = 'Assign Client';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['assignProductsButton']['button']['assignProducts'] = 'Assign Products';

$_LANG['addonAA']['home']['assignClientModal']['modal']['AssignClientModal'] = 'Assign Client';
$_LANG['addonAA']['home']['assignClientModal']['assignClientForm']['client']['client'] = 'Client';
$_LANG['addonAA']['home']['assignClientModal']['assignClientForm']['service']['service'] = 'Service';
$_LANG['addonAA']['home']['assignClientModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['addonAA']['home']['assignClientModal']['baseCancelButton']['title'] = 'Cancel';

$_LANG['addonAA']['home']['assignProductsModal']['modal']['AssignProductsModal'] = 'Assign Reusable Products';
$_LANG['addonAA']['home']['assignProductsModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['addonAA']['home']['assignProductsModal']['baseCancelButton']['title'] = 'Cancel';
$_LANG['addonAA']['home']['assignClientProvider']['update']['service']['error']['hasMachine'] = 'The selected service already has a server assigned';
$_LANG['addonAA']['home']['assignClientProvider']['update']['service']['error']['noClient'] = 'Please choose the client first';
$_LANG['addonAA']['home']['assignClientProvider']['update']['service']['error']['noService'] = 'Please choose the service first';
$_LANG['addonAA']['home']['assignClientProvider']['update']['service']['success'] = 'The server has been assigned to selected service successfully';
$_LANG['addonAA']['home']['assignProductsProvider']['update']['success'] = 'Products that can reuse this machine have been defined successfully';
$_LANG['addonAA']['home']['mainContainer']['dedicatedPage']['table']['client'] = 'Client';
/************ VPS ****************/

$_LANG['addonAA']['breadcrumbs']['Vps'] = 'VPS';
$_LANG['addonAA']['pagesLabels']['Home']['VPS'] = 'VPS';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['state'] = 'State';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['displayName'] = 'Displayed Name';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['client'] = 'Client';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['service'] = 'Service';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['table']['reuse'] = 'Reusable';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['assignClient']['button']['assignClient'] = 'Assign Client';
$_LANG['addonAA']['home']['mainContainer']['vpsPage']['assignProductsButton']['button']['assignProducts'] = 'Assign Products';



//***************** WHMCS ***********************//

$_LANG['mainContainer']['serverOptions']['vps'] = 'VPS';
$_LANG['mainContainer']['serverOptions']['dedicated'] = 'Dedicated';


$_LANG['addonAA']['breadcrumbs']['LoggerManager'] = 'Logs';
$_LANG['addonAA']['loggerManager']['button']['massDeleteLoggerButton'] = 'Delete Logs';
$_LANG['addonAA']['loggerManager']['mainContainer']['loggercont']['table']['id'] = 'Id';
$_LANG['addonAA']['loggerManager']['mainContainer']['loggercont']['table']['message'] = 'Message';
$_LANG['addonAA']['loggerManager']['mainContainer']['loggercont']['table']['type'] = 'Type';
$_LANG['addonAA']['loggerManager']['mainContainer']['loggercont']['table']['date'] = 'Date';
$_LANG['addonAA']['loggerManager']['mainContainer']['loggercont']['deleteLoggerModalButton']['button']['deleteLoggerModalButton'] = 'Delete Log';

$_LANG['addonAA']['loggerManager']['deleteLoggerModal']['modal']['deleteLoggerModal'] = 'Delete Log';
$_LANG['addonAA']['loggerManager']['deleteLoggerModal']['deleteLoggerForm']['confirmLabelRemoval'] = 'Are you sure that you want to delete the log?';
$_LANG['addonAA']['loggerManager']['deleteLoggerModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['addonAA']['loggerManager']['deleteLoggerModal']['baseCancelButton']['title'] = 'Cancel';

$_LANG['addonAA']['loggerManager']['log']['delete']['success'] = 'The log has been deleted successfully';
$_LANG['addonAA']['loggerManager']['logs']['delete']['success'] = 'The selected logs have been deleted successfully';

$_LANG['addonAA']['loggerManager']['massDeleteLoggerModal']['modal']['massDeleteLoggerModal'] = 'Delete Logs';
$_LANG['addonAA']['loggerManager']['massDeleteLoggerModal']['deleteLoggerForm']['confirmLabelRemoval'] = 'Are you sure that you want to delete the selected logs?';
$_LANG['addonAA']['loggerManager']['massDeleteLoggerModal']['baseAcceptButton']['title'] = 'Confirm';
$_LANG['addonAA']['loggerManager']['massDeleteLoggerModal']['baseCancelButton']['title'] = 'Cancel';

//vps logs
$_LANG['addonAA']['loggerManager']['logs']['vps']['upgradeDowngrade']['success'] = "VPS Machine: :machineName: has been upgraded/downgraded for client :client:";
$_LANG['addonAA']['loggerManager']['logs']['vps']['upgradeDowngrade']['optionAdding']['error'] = "Error: Adding option :option: to VPS Machine :machineName: during upgrade/downgrade.";


$_LANG['addonAA']['loggerManager']['logs']['vps']['email']['error']['actionNotFound'] = "Error: Action not found during email forwarding for service :service:.";
$_LANG['addonAA']['loggerManager']['logs']['vps']['reuse']['success'] = "VPS Machine: :machineName: has been created from reusable machines for client :client:";
$_LANG['addonAA']['loggerManager']['logs']['vps']['reinstall']['success'] = "VPS Machine: :machineName: has been reinstalled successfully for client :client:";
$_LANG['addonAA']['loggerManager']['logs']['vps']['create']['success'] = "VPS Machine has been created successfully for client :client: service :service:";
//dedicated logs
$_LANG['addonAA']['loggerManager']['logs']['vps']['reassinged'] = "VPS Machine has been reassigned successfully for client :client: service :service:";
$_LANG['addonAA']['loggerManager']['logs']['dedicated']['create']['success'] = "Dedicated server has been automatically created for client :client: service :service: from reusable product";
$_LANG['addonAA']['loggerManager']['logs']['dedicated']['suspend']['terminate']['success'] = "Dedicated server :machineName: has been terminated successfully for client :client: service :service: on suspend action";
$_LANG['addonAA']['loggerManager']['logs']['dedicated']['suspend']['toRescue']['success'] = "Dedicated server :machineName: has been booted to rescue mode successfully for client :client: service :service: on suspend action";


$_LANG['addonAA']['loggerManager']['logs']['vps']['upgradeDowngrade']['optionAdding']['sameAsUse'] = "Error: Unable to change offer to VPS Machine :machineName: during upgrade/downgrade. Options is the same as usage";



$_LANG['addonAA']['loggerManager']['logs']['account']['suspend']['success'] = "Service :service: has been suspended successfully";
$_LANG['addonAA']['loggerManager']['logs']['account']['create']['success'] = "Service :service: has been created successfully";
$_LANG['addonAA']['loggerManager']['logs']['account']['changePackage']['success'] = "Service :service: has been upgraded/downgraded successfully";
$_LANG['addonAA']['loggerManager']['logs']['account']['terminate']['success'] = "Service :service: has been terminated successfully";
$_LANG['addonAA']['loggerManager']['logs']['account']['unsuspend']['success'] = "Service :service: has been unsuspended successfully";




$_LANG['addonAA']['loggerManager']['logs']['account']['suspend']['error'] = "Error during suspend for service :service:. Message: :messageName:";
$_LANG['addonAA']['loggerManager']['logs']['account']['changePackage']['error'] = "Error during change package for service :service:. Message: :messageName:";
$_LANG['addonAA']['loggerManager']['logs']['account']['terminate']['error'] = "Error during terminate for service :service:. Message: :messageName:";
$_LANG['addonAA']['loggerManager']['logs']['account']['unsuspend']['error'] = "Error during unsuspend package for service :service:. Message: :messageName:";
$_LANG['addonAA']['loggerManager']['logs']['account']['create']['error'] = "Error during create for service :service:. Message: :messageName:";