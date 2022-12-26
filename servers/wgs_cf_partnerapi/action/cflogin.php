<?php
use Illuminate\Database\Capsule\Manager as Capsule;

global $CONFIG;
global $whmcs;
$systemURL = $systemurl = (empty($CONFIG['SystemSSLURL'])) ? $CONFIG['SystemURL'] : $CONFIG['SystemSSLURL'];
$moduleURL = $url = $systemurl . '/modules/servers/wgs_cf_partnerapi/';

# include classes
if (!class_exists('CF_HOST_API')) {
    require(dirname(__DIR__) . "/classes/class.cloudflareAPI.php");
}
if (file_exists(dirname(__DIR__) . '/classes/class.php')) {
    require_once dirname(__DIR__) . '/classes/class.php';
}

$cfUserdetail = Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $params['clientsdetails']['email'])->first();

if ($cfUserdetail) {
    $emailAddress = $cfUserdetail->email;
    $cfpassword = $password = $cfUserdetail->password;
    $username = $cfUserdetail->username;
}
if (empty($emailAddress)) {
    $emailAddress = !empty($params['customfields']['CloudFlare Email Address']) ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
    if (empty($emailAddress))
        $emailAddress = $params['clientsdetails']['email'];
}
if (empty($username)) {
    $username = !empty($params['customfields']['CloudFlare Username']) ? $params['customfields']['CloudFlare Username'] : $params['customfields']['cf_username'];
    if (empty($username))
    	$username = $params['clientsdetails']['firstname'].$params['serviceid'];
        //$username = str_replace('@', '', $emailAddress);
}
 
if (empty($cfpassword)) {
    $cfpassword = !empty($params['customfields']['CloudFlare Password']) ? $params['customfields']['CloudFlare Password'] : $params['customfields']['cf_password'];
    if(empty($cfpassword)){
		$cfpassword = $CF->wgs_cf_generateRandomString(10);
    }  	
}
if (isset($_POST['cflogin']) && !empty($_POST['cflogin'])) {
    $emailAddress = $whmcs->get_req_var('cfemail');
    $cfpassword = $whmcs->get_req_var('cfpw');
    // $usernameArr = explode('@', $emailAddress);
    //$username = $usernameArr[0]; 
	$username = $params['clientsdetails']['firstname'].$params['serviceid'];
}
 

$getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

$hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
$request_body = array("cloudflare_email" => $emailAddress, "cloudflare_pass" => $cfpassword, "cloudflare_username" => $username);
 
$result = $hostAPI->user_create($emailAddress, $cfpassword, $username);

logModuleCall("CF Partner", "user create (clientarea)", $request_body, (array) $result);
$errors = '';
$zone_error = $success = '';
if (strchr($result->msg, 'Password failed for CloudFlare account')) {
    if ($username) {
        $errors .= "This Email Address (" . $username . ") is already registered. Please try another!";
    }

    $errors .= "If you want to proceed with your existing account, then you must enter correct Email Address and Password.";
}
if ($result->msg)
    $errors .= $result->msg;
if ($result->result == 'success' && !empty($result->response)) {
	$emailAddressFieldId = $CF->getCustomfieldId('cf_email|CloudFlare Email Address', $params['pid']);
    $passwordFieldId = $CF->getCustomfieldId('cf_password|CloudFlare Password', $params['pid']);
    $usernameFieldId = $CF->getCustomfieldId('cf_username|CloudFlare Username', $params['pid']);
    $userkeyfieldId = $CF->getCustomfieldId('cf_user_key|Cloudflare User Key', $params['pid']);
    $userAPIkeyfieldId = $CF->getCustomfieldId('cf_user_api_key|Cloudflare User API Key', $params['pid']);
    $command = "updateclientproduct";
    $adminuser = '';
    $values["serviceid"] = $params["serviceid"];
    $values['domain'] = $domain;
    $values["customfields"] = base64_encode(serialize(array($emailAddressFieldId => $result->response->cloudflare_email, $passwordFieldId => $cfpassword, $usernameFieldId => $result->response->cloudflare_username, $userkeyfieldId => $result->response->user_key, $userAPIkeyfieldId => $result->response->user_api_key)));
    $results = localAPI($command, $values, $adminuser);

    try {
        if (empty($cfUserdetail)) {
            Capsule::table('mod_cf_zone')->insert(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
        } else {
            Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $emailAddress)->update(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
        }
    } catch (Exception $ex) {
        logActivity("Unable to insert/update table: mod_cf_zone error: {$ex->getMessage()}");
    }
	$CF->ApiEmail = $result->response->cloudflare_email;
	$CF->ApiKey = $result->response->user_api_key;
	$zoneResult = $CF->getSingleZone($domain);
	logModuleCall("CF Partner", "get zone detail (clientarea)", ['zone' => $domain], $zoneResult);
	if(empty($zoneResult['result'][0]['id'])){    
		$cfapikey = $result->response->user_api_key;
		logModuleCall("CF Partner", "WHMCS API updateclientproduct, Clodflare User Created (clientarea)", $values, $results);

		$productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();
		$plan = $productSetting->plan;
		$ip = $productSetting->dns_ip;
		$zone_type = $productSetting->zone_type;

		$resolve_to = 'cloudflare-resolve-to.' . $domain;
		$subdomains = 'www';
		if ($zone_type == 'full')
			$zoneType = 'full_zone_set';
		elseif ($zone_type == 'partial') {
			$zoneType = 'zone_set';
		}
		if (isset($_POST['cflogin']) && !empty($_POST['cflogin'])) {
			$zone_add = $hostAPI->zone_set($result->response->user_key, $domain, $resolve_to, $subdomains, $zoneType);

			logModuleCall("CF Partner", "Add zone (create account) - (clientarea)", $domain, (array) $zone_add);
			if ($zone_add->result == 'error') {
				$errors = "Add zone failed {$domain} <br/>Error code: {$zone_add->err_code}, Error message: {$zone_add->msg}";
			}
			if ($zone_add->result == 'success') {
				$success = $language['cf_website_added_success'];
				$CF->ApiEmail = $result->response->cloudflare_email;
				$CF->ApiKey = $result->response->user_api_key;
				$zoneResult = $CF->getSingleZone($domain);
				logModuleCall("CF Partner", "get zone detail (clientarea)", ['zone' => $domain], $zoneResult);
				$CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
				$CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
				if (!empty($zoneResult['result'][0]['name_servers'])) {
					$domainId = '';
					$doaminDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
					$domainId = $doaminDetail->id;
					if ($domainId)
						$CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId, $domain);
				}

				if ($plan) {
					if ($plan == "FREE")
						$planTag = 'Free';
					elseif ($plan == "PARTNERS_PRO")
						$planTag = 'CF_RESELLER_PRO';
					elseif ($plan == "PARTNERS_BIZ")
						$planTag = 'CF_RESELLER_BIZ';
					elseif ($plan == "PARTNERS_ENT")
						$planTag = 'CF_RESELLER_ENT';
				}

				if ($planTag != 'Free') {
					$plan_sub = $hostAPI1->reseller_sub_new($result->response->user_key, $domain, $planTag);
					logModuleCall("CF Partner", "Add sub plan to Cloudflare Partner", array('zone' => $domain, 'plan_tag' => $planTag), (array) $plan_sub);
					if ($plan_sub->result == "success") {

						$CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
						$subCustomfiledId = Capsule::table("tblcustomfields")->where("type", "product")->where("relid", $params['pid'])->where("fieldname", 'Subscription ID')->first();

						$command = 'UpdateClientProduct';
						$postData = array(
							'serviceid' => $params['serviceid'],
							'customfields' => base64_encode(serialize(array($subCustomfiledId->id => $plan_sub->response->sub_id)))
						);
						$adminUsername = '';

						//$results = localAPI($command, $postData, $adminUsername);
					} else {
						$results['message'] = $plan_sub->msg;
					}
				}
				
				$always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
				logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount)", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
				$autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
				logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount)", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);

				$server_ip = !empty($params['customfields']['dns_ip']) ? $params['customfields']['dns_ip'] : $ip;
				if (!empty($server_ip)) {
					$dnsData = [
						[
							'zone_id' => $zoneid,
							'cfdnstype' => 'A',
							'cfdnsname' => 'www',
							'cfdnsvalue' => $server_ip,
							'cfdnsttl' => '120',
							'proxied' => ($proxy == '1') ? 'true' : 'false',
						],
						[
							'zone_id' => $zoneid,
							'cfdnstype' => 'A',
							'cfdnsname' => '@',
							'cfdnsvalue' => $server_ip,
							'cfdnsttl' => '120',
							'proxied' => ($proxy == '1') ? 'true' : 'false',
						]
					];
					foreach ($dnsData as $dnsVal) {
						$createDns = $CF->createDNSRecord($dnsVal);
						if ($createDns['result'] == 'error') {
							logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount)", $dnsVal, 'Error: ' . $createDns['data']['apierror']);
							logActivity("Set DNS record failed $domain: " . $createDns['data']['apierror']);
						} else {
							logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount)", $dnsVal, 'Dns record has been successfully added');
							logActivity("DNS record has been steup zone :$domain");
						}
					}
				}
			}
        }
    }else{
		$success = $language['cf_website_added_success'];
	}
}

$vars = array(
    'url' => $url,
    'account' => $details,
    'wgs_lang' => $language,
    'user_lang' => $clientLanguage,
    'login_error' => $errors,
    'zone_error' => $zone_error,
    'emailaddress' => $emailAddress,
    'cfpassowrd' => $password,
	'loginsuccess' => $success
);
$templateFile = "template/cflogin/cflogin";
?>
