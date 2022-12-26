<?php

/* * ****************************************************************
 *  WGS Cloudflare WHMCS Provisioning Module By whmcsglobalservices.com
 *  Copyright whmcsglobalservices, All Rights Reserved
 * 
 *  Release: 01 May 2016
 *  WHMCS Version: v6,v7
 *  Version: 5.0.7
 *  Update Date: 10 Oct, 2021
 * 
 *  By WHMCSGLOBALSERVICES    https://whmcsglobalservices.com
 *  Contact                   info@whmcsglobalservices.com
 *  
 *  This module is made under license issued by whmcsglobalservices.com
 *  and used under all terms and conditions of license.    Ownership of 
 *  module can not be changed.     Title and copy of    module  is  not
 *  available to any other person.
 * 
 *  @owner <whmcsglobalservices.com>
 *  @author <WHMCSGLOBALSERVICES>
 * ********************************************************** */

use WGS\MODULES\CLOUDFLARE\wgs_cloudflare as cloudflare;
use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

if (file_exists(__DIR__ . '/classes/class.php'))
    include_once __DIR__ . '/classes/class.php';

function wgs_cf_partnerapi_MetaData()
{
    return array(
        'DisplayName' => 'WGS Cloudflare Reseller',
    );
}

function wgs_cf_partnerapi_ConfigOptions()
{
    global $whmcs;
    $productid = (int) $whmcs->get_req_var("id");

    $CF = new cloudflare();
    $license_status = $CF->checkLicense();

    $CF->CreateDbTable();
    $CF->__createProductAddons($productid);

    $createProductCustomFields = $CF->createProductCustomFields($productid);
    $createProductConfigurableOption = $CF->createProductConfigurableOption($productid);
     
    $status = '<span style="color:#ff0000;">' . $license_status . '</span>';
    if ($license_status == 'Active')
        $status = '<span style="color:#068206;">' . $license_status . '</span>';
    $configarray = array(
        "License Status" => array(
            "Type" => "na",
            "Description" => $status
        )
    );
    return $configarray;
}

function wgs_cf_partnerapi_CreateAccount(array $params)
{   
    $serviceid = $params['serviceid'];
    $domain = $params['customfields']['cloudflare_domain'];
    if ($domain == '')
        $domain = $params['domain'];
    $name = $params['clientsdetails']['email'];
    $user_id = $params['userid'];
    $CF = new cloudflare();
    $CF->updateDBTable();

    $license_status = $CF->checkLicense();
    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();

    $plan = $productSetting->plan;
    $member = $productSetting->member;
    $create_cf_user = $productSetting->user;
    $user_accountid = $productSetting->accountid;
    $account_type = $productSetting->member_type;
    $zone_type = $productSetting->zone_type;
    $ip = $productSetting->dns_ip;
    $status = $productSetting->status;
    $proxy = $productSetting->proxy;

    if ($status == 0) {
        return "Product is disabled from Cloudflare Reseller Addon Module >> Product Settings.";
    }

    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
    
    if ($license_status != 'Active') {
        return "Your license is " . $license_status . ".";
    }

    $where = [
        ['userid', '<>', $user_id],
        ['domain', '=', $domain],
    ];
                
    $checkDomainExist = Capsule::table('tbldomains')->where($where)->count(); 
    $hostingDomainExist = Capsule::table('tblhosting')->where($where)->count();

    $getdomain = Capsule::table('mod_cf_manage_domains')->where('zone', $domain)->first();
     
    if(count($getdomain) != 0 || $checkDomainExist != 0  || $hostingDomainExist != 0){
  
       if($getdomain->uid == $user_id){
            $error = "Domain (".$domain.") already exists with  User";
       }else{
            $error = "Domain (".$domain.") already exists with another User";
       }
       return $error;
    } 

    if ($getSetting->servicetype != 'hosting_partner') {

        if ($create_cf_user != 0) {
            $getCfUserId = Capsule::table('mod_cf_manage_users')->where('uid', $params['userid'])->first();
            if ($getCfUserId->cf_uid == '') {
                $create_user = $CF->create_user($name, $account_type);
                if ($create_user['success'] == 1) {
                    $accountId = $create_user['result']['id'];
                    //            $CF->updateCustomFieldValues('user_account_id', $serviceid, $accountId, $params['pid']);
                    $CF->saveUserId(['uid' => $params['userid'], 'cf_uid' => $accountId]);
                } else {
                    return "Error Code: " . $create_user['data']['cferrorcode'] . ", Error Message: " . $create_user['data']['apierror'];
                }
            } else {
                $accountId = $getCfUserId->cf_uid; //$params['customfields']['user_account_id'];
            }
        } else {
            $accountId = $user_accountid;
        }
        if ($accountId) {
            if ($member == '1' && $create_cf_user != 0) {
                $getrole = $CF->get_roles($accountId);
                $roleID = $getrole['result'][0]['id'];
                if ($roleID && $params['customfields']['member_id'] == '') {
                    $createMember = $CF->add_member($accountId, $roleID, $params);
                    if ($createMember['result'] == 'error') {
                        $result = "Error Code: " . $createMember['data']['cferrorcode'] . ", Error Message: " . $createMember['data']['apierror'];
                    } else {
                        $memberId = $createMember['result']['id'];
                        $CF->updateCustomFieldValues('member_id', $serviceid, $memberId, $params['pid']);
                    }
                }
            }

            $createZone = $CF->create_zone($domain, $accountId, $zone_type, $serviceid);
            
            if ($createZone['result'] == 'error' && $createZone['data']['cferrorcode'] == 1061) {
               
                $getDomainDetail = $CF->getDomainInfo($domain);
                 
                $zoneId = $getDomainDetail['result'][0]['id'];
                $dName = $getDomainDetail['result'][0]['name'];
                $CF->updateCustomFieldValues('cloudflare_domain', $serviceid,$dName, $params['pid']);
                $CF->updateCustomFieldValues('zone_id', $serviceid, $zoneId, $params['pid']);
                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $dName, 'zoneid' => $zoneId]);
                return 'success';
        
            }else if($createZone['result'] == 'error') {
                return "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message: " . $createZone['data']['apierror'];
            } else {
                $CF->updateCustomFieldValues('cloudflare_domain', $serviceid, $domain, $params['pid']);
                $CF->updateCustomFieldValues('zone_id', $serviceid, $createZone['result']['id'], $params['pid']);
                $zoneSubId = '';
                if ($plan != 'FREE') {
                    $createZoneSub = $CF->create_zone_subscription($domain, $createZone['result']['id'], $params);
                    if ($createZoneSub['result'] == 'error') {
                        return "Error Code: " . $createZoneSub['data']['cferrorcode'] . ", Error Message: " . $createZoneSub['data']['apierror'];
                    } else {
                        $zoneSubId = $createZoneSub['result']['id'];
                        //$CF->updateCustomFieldValues('zone_sub_id', $serviceid, $zoneSubId, $params['pid']);
                        $result = 'success';
                    }
                } else {
                    //$CF->updateCustomFieldValues('zone_sub_id', $serviceid, '', $params['pid']);
                }

                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id'], 'sub_id' => $zoneSubId]);

                if ($createZone['result']['name_servers'] != '') {
                    $domainId = '';
                    $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                    $domainId = $domainDetail->id;
                    if ($domainId)
                        $CF->updateNS($createZone['result']['name_servers'], $domainId, $domain);
                }

                $CF->zoneidentifier = $zoneid = $createZone['result']['id'];
            }
        }
    } else {
        if (!class_exists('CF_HOST_API')) {
            require(__DIR__ . '/classes/class.cloudflareAPI.php');
        }
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        
        if(empty($emailAddress)){
            $emailAddress = $params['clientsdetails']['email'];
        }
        $emailAddress = trim($emailAddress);
        $countExistingUser = Capsule::table('mod_cf_zone')->where('email',$emailAddress)->where('uid','!=',$params['userid'])->count();
        
        if($countExistingUser > 0){
            return "This account ( $emailAddress ) already exists with another User";
        }
        
        $cfUserdetail = Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email',  $emailAddress)->first();

        if ($cfUserdetail) {
            $emailAddress = $cfUserdetail->email;
            $cfpassword = $password = $cfUserdetail->password;
            //$username = $cfUserdetail->username;
        }
        
        if ($emailAddress == '') {
            $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
            if ($emailAddress == '')
                $emailAddress = $params['clientsdetails']['email'];
        }
        if ($username == '') {
            $username = $params['customfields']['CloudFlare Username'] ? $params['customfields']['CloudFlare Username'] : $params['customfields']['cf_username'];
            if ($username == '')
                $username = $params['clientsdetails']['firstname'].$params['serviceid'];
               // $username = str_replace('@', '', $emailAddress);
        }
        if ($cfpassword == '') {
            $cfpassword = $params['customfields']['CloudFlare Password'] ? $params['customfields']['CloudFlare Password'] : $params['customfields']['cf_password'];
            if ($cfpassword == '')
                $cfpassword = $CF->wgs_cf_generateRandomString(10);
        }
        
        $hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
        $request_body = array("cloudflare_email" => $emailAddress, "cloudflare_pass" => $cfpassword, "cloudflare_username" => $username);
        $result = $hostAPI->user_create($emailAddress, $cfpassword, $username);
       
        logModuleCall("CF Partner", "user_create", $request_body, (array) $result);
         
        if ($result->result == "success") {
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
                if ($cfUserdetail == '') {
                    Capsule::table('mod_cf_zone')->insert(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
                } else {
                    Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $emailAddress)->update(['password' => $cfpassword, 'username' => $username]);
                }
            } catch (Exception $ex) {
                logActivity("Unable to insert/update table: mod_cf_zone error: {$ex->getMessage()}");
            }

            logModuleCall("CF Partner", "WHMCS API updateclientproduct, Clodflare User Created", $values, $results);
            $resolve_to = 'cloudflare-resolve-to.' . $domain;
            $subdomains = 'www';
            if ($zone_type == 'full')
                $zoneType = 'full_zone_set';
            elseif ($zone_type == 'partial') {
                $zoneType = 'zone_set';
            }
            
            $zone_add = $hostAPI->zone_set($result->response->user_key, $domain, $resolve_to, $subdomains, $zoneType);
            
            if($zone_add->result == 'error' && $zone_add->err_code == 219){
                $zoneResult = $CF->getSingleZone($domain);
                logModuleCall("CF Partner", "get zone detail", ['zone' => $domain], $zoneResult);
                $zoneid = $zoneResult['result'][0]['id'];
                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                return 'success';
            }
        
            logModuleCall("CF Partner", "Add zone (create account)", $domain, (array) $zone_add);

            if ($zone_add->result == 'error') {
                    return $zone_add->msg;
            }
            if ($zone_add->result == 'success') {
                $CF->ApiEmail = $result->response->cloudflare_email;
                $CF->ApiKey = $result->response->user_api_key;
                $zoneResult = $CF->getSingleZone($domain);
                logModuleCall("CF Partner", "get zone detail", ['zone' => $domain], $zoneResult);
                $CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                if ($zoneResult['result'][0]['name_servers'] != '') {
                    $domainId = '';
                    $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                    $domainId = $domainDetail->id;
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
                    $plan_sub = $hostAPI->reseller_sub_new($result->response->user_key, $domain, $planTag);
                    logModuleCall("CF Partner", "Add sub plan to Cloudflare Partner", array('zone' => $domain, 'plan_tag' => $planTag), (array) $plan_sub);
                    if ($plan_sub->result == "success") {

                        $subCustomfiledId = Capsule::table("tblcustomfields")->where("type", "product")->where("relid", $params['pid'])->where("fieldname", 'Subscription ID')->first();
                        $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
                        $command = 'UpdateClientProduct';
                        $postData = array(
                            'serviceid' => $params['serviceid'],
                            'customfields' => base64_encode(serialize(array($subCustomfiledId->id => $plan_sub->response->sub_id)))
                        );
                        $adminUsername = '';

                        //$results = localAPI($command, $postData, $adminUsername);
                    } else {
                        return $plan_sub->msg;
                    }
                }
            }
        }
    }


    $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
    logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount)", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
    $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
    logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount)", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);

    $server_ip = $params['customfields']['dns_ip'] ? $params['customfields']['dns_ip'] : $ip;
    if ($server_ip) {
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
    $result = 'success';
    //    }
    //    }
    return $result;
}

function wgs_cf_partnerapi_SuspendAccount($params)
{
    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();
    $status = $productSetting->status;
    if ($status == 0) {
        return "Product is disabled from Cloudflare Reseller Addon Module >> Product Settings.";
    }
    $CF = new cloudflare();
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    if ($getSetting->servicetype == 'hosting_partner') {
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        $cf_user_api_key = $params['customfields']['Cloudflare User API Key'] ? $params['customfields']['Cloudflare User API Key'] : $params['customfields']['cf_user_api_key'];

        $CF->ApiEmail = $emailAddress;
        $CF->ApiKey = $cf_user_api_key;
    }
    $serviceid = $params['serviceid'];
    $getAllZones = Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('sid', $serviceid)->get();
    if (count($getAllZones) > 0) {
        foreach ($getAllZones as $zone) {
            $zoneid = $zone->zoneid;
            if ($zoneid) {
                $CF->zoneidentifier = $zoneid;
                $value = true;
                $result = $CF->pauseUnpauseSite($value);
                logModuleCall('WGS CF  Provisioning Module', 'Suspend (pause)', ['pause' => $value], $result);
            }
        }
        return 'success';
    } else {
        return "Error: Zone id missing!";
    }

    $result = "success";
    return $result;
}

function wgs_cf_partnerapi_UnsuspendAccount($params)
{
    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();
    $status = $productSetting->status;
    if ($status == 0) {
        return "Product is disabled from Cloudflare Reseller Addon Module >> Product Settings.";
    }

    $CF = new cloudflare();
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    if ($getSetting->servicetype == 'hosting_partner') {
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        $cf_user_api_key = $params['customfields']['Cloudflare User API Key'] ? $params['customfields']['Cloudflare User API Key'] : $params['customfields']['cf_user_api_key'];

        $CF->ApiEmail = $emailAddress;
        $CF->ApiKey = $cf_user_api_key;
    }
    $serviceid = $params['serviceid'];
    $getAllZones = Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('sid', $serviceid)->get();
    if (count($getAllZones) > 0) {
        foreach ($getAllZones as $zone) {
            $zoneid = $zone->zoneid;
            if ($zoneid) {
                $CF->zoneidentifier = $zoneid;
                $value = false;
                $result = $CF->pauseUnpauseSite($value);
                logModuleCall('WGS CF  Provisioning Module', 'Un-Suspend (unpause)', ['pause' => $value], $result);
            }
        }
        return 'success';
    } else {
        return "Error: Zone id missing!";
    }

    $result = "success";
    return $result;
}

function wgs_cf_partnerapi_TerminateAccount($params)
{
    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();
    $status = $productSetting->status;

    if ($status == 0) {
        return "Product is disabled from Cloudflare Reseller Addon Module >> Product Settings.";
    }

    $serviceid = $params['serviceid'];
    $CF = new cloudflare();
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    if ($getSetting->servicetype == 'hosting_partner') {
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        $cf_user_api_key = $params['customfields']['Cloudflare User API Key'] ? $params['customfields']['Cloudflare User API Key'] : $params['customfields']['cf_user_api_key'];

        $CF->ApiEmail = $emailAddress;
        $CF->ApiKey = $cf_user_api_key;
    }

    $getAllZones = Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('sid', $serviceid)->get();
    if (count($getAllZones) > 0) {
        foreach ($getAllZones as $zone) {
            $zoneid = $zone->zoneid;
            if ($zoneid) {
                $del_zone = $CF->delete_zone($zoneid);
                logModuleCall('WGS CF  Provisioning Module', 'delete domain (terminate)', ['zone' => $zone->zone, 'zoneid' => $zoneid], $del_zone);
                if ($del_zone['success'] == 1) {
                    $CF->updateCustomFieldValues('zone_id', $serviceid, '', $params['pid']);
                    $CF->updateCustomFieldValues('zone_sub_id', $serviceid, '', $params['pid']);
                    $CF->deleteZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $zone->zone]);
                }
            }
        }

        if ($getSetting->servicetype != 'hosting_partner') {
            $memberId = $params['customfields']['member_id'];
            if ($memberId) {
                $getCfUserId = Capsule::table('mod_cf_manage_users')->where('uid', $params['userid'])->first();
                $accountId = $getCfUserId->cf_uid;
                if ($accountId) {
                    $deleteMember = $CF->remove_member($accountId, $memberId);
                    if ($createMember['success'] == 1) {
                        $CF->updateCustomFieldValues('member_id', $serviceid, '', $params['pid']);
                    }
                }
            }
        }
        return 'success';
    } else {
        return "Error: Zone id missing!";
    }
}

function wgs_cf_partnerapi_ClientArea($params)
{
    $error = $success = '';
    if (isset($_POST['upgardeSub']) && $_POST['upgardeSub'] == 'true') {
        include_once __DIR__ . '/includes/upgrade_ajax.php';
    }

    $domain = $defaultDomain = $params['customfields']['cloudflare_domain'];
    if ($domain == '') {
        $domain = $params['domain'];
    }
    if ($domain == '') {
        return "<font color='red'>Domain is missing!</font>";
    }

    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $params['pid'])->first();
    $plan = $productSetting->plan;
    $numberOfDomains = $productSetting->domains;
    $user_accountid = $productSetting->accountid;
    $create_cf_user = $productSetting->user;

    if ($params['configoptions']['additional_domains'] != '') {
        $numberOfDomains = $numberOfDomains + $params['configoptions']['additional_domains'];
    }

    $status = $productSetting->status;
    $proxy = $productSetting->proxy;
    if ($status == 0) {
        return "<font color='red'>Product is disabled, Please contact with support!</font>";
    }
    $CF = new cloudflare();

    $license_status = $CF->checkLicense();
    if ($license_status != 'Active') {
        return "<font color='red'>" . $license_status . ' License, Please contact with support!' . "</font>";
    }

    $clientLanguage = $CF->wgsCfGetClientLanguage($params);
    $language = $CF->wgsCfGetLang($clientLanguage);

    $getCfUserId = Capsule::table('mod_cf_manage_users')->where('uid', $params['userid'])->first();

    $accountId = $getCfUserId->cf_uid;

    if ($create_cf_user == 0) {
        $accountId = $user_accountid;
    }

    global $whmcs;
    $error = $success = '';
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    if ($getSetting->servicetype == 'hosting_partner') {
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        $cf_user_api_key = $params['customfields']['Cloudflare User API Key'] ? $params['customfields']['Cloudflare User API Key'] : $params['customfields']['cf_user_api_key'];
        $cf_user_key = $params['customfields']['Cloudflare User Key'] ? $params['customfields']['Cloudflare User Key'] : $params['customfields']['cf_user_key'];

        $CF->ApiEmail = $emailAddress;
        $CF->ApiKey = $cf_user_api_key;

        require_once __DIR__ . '/action/cflogin.php';
    }

    if (isset($_POST['adddomain']) && $_POST['adddomain'] == 'true') {
        if ($_POST['domainname']) {
            $domain = $whmcs->get_req_var('domainname');
            $user_id = $params['userid'];
            $zone_type = $productSetting->zone_type;
            $ip = $productSetting->dns_ip;

            $addedDominsTotal = Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count();
            if ($addedDominsTotal >= $numberOfDomains) {
                $error = "You have reached your domain max limit!";
            } else {
                if ($getSetting->servicetype == 'reseller') {
                    $where = [
                        ['userid', '<>', $user_id],
                        ['domain', '=', $domain],
                    ];
     
                    $checkDomainExist = Capsule::table('tbldomains')->where($where)->count();
                    $hostingDomainExist = Capsule::table('tblhosting')->where($where)->count();

                    $getdomain = Capsule::table('mod_cf_manage_domains')->where('zone', $domain)->first();

                    if (count($getdomain) != 0 || $checkDomainExist != 0  || $hostingDomainExist != 0) {
                        if ($getdomain->uid == $user_id) {
                            $error = "Domain (" . $domain . ") already exists with  User";
                        } else {
                            $error = "Domain (" . $domain . ") already exists with another User";
                        }
                    } else {
                        $getZonedetail = $CF->getSingleZone($domain);
            
                        if ($getZonedetail['result'] == 'error') {
                            $error = "Error Code: " . $getZonedetail['data']['cferrorcode'] . ", Error Message: " . $getZonedetail['data']['apierror'];             
                        } elseif ($getZonedetail['result'][0]['id'] != '') {
                            $getzoneid = $getZonedetail['result'][0]['id'];
                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $getzoneid]);
                            $domainstatus = "Zone/Website added successfully";
                        } else {
                            $createZone = $CF->create_zone($domain, $accountId, $zone_type, $params['serviceid']);

                            if ($createZone['result'] == 'error') {
                                $error = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message: " . $createZone['data']['apierror'];
                            } else {
                                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id']]);
                                if ($createZone['result']['name_servers'] != '') {
                                    $domainId = '';
                                    $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                    $domainId = $domainDetail->id;
                                    if ($domainId) {
                                        $CF->updateNS($createZone['result']['name_servers'], $domainId, $domain);
                                    }
                                }
                                //$success = $language['cf_website_added_success'];
                                $domainstatus = $language['cf_website_added_success_domain'];
                                $CF->zoneidentifier = $createZone['result']['id'];
            
                                if ($plan != 'FREE') {
                                    $createZoneSub = $CF->create_zone_subscription($domain, $createZone['result']['id'], $params);
                                    if ($createZoneSub['result'] == 'error') {
                                        $error = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message: " . $createZone['data']['apierror'];
                                    } else {
                                        $zoneSubId = $createZoneSub['result']['id'];
                                        $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id'], 'sub_id' => $zoneSubId]);
                                        //$success = $language['cf_website_added_success'];
                                        $domainstatus = $language['cf_website_added_success_domain'];
                                    }
                                }
                            }
                        }
                    }
                } elseif ($getSetting->servicetype == 'hosting_partner') {
                    if (!class_exists('CF_HOST_API')) {
                        require(__DIR__ . '/classes/class.cloudflareAPI.php');
                    }
                    $where = [
                        ['userid', '<>', $user_id],
                        ['domain', '=', $domain],
                    ];
                    
                    $checkDomainExist = Capsule::table('tbldomains')->where($where)->count();
                    $hostingDomainExist = Capsule::table('tblhosting')->where($where)->count();
     
                    $getdomain = Capsule::table('mod_cf_manage_domains')->where('zone', $domain)->first();
                   
                    if (count($getdomain) != 0 || $checkDomainExist != 0  || $hostingDomainExist != 0) {   
                        if ($getdomain->uid == $user_id) {
                            $error = "Domain already exists with  User";
                        } else {
                            $error = "Domain already exists with another User";
                        }
                    } else {
                        $getZonedetail =  $CF->getSingleZone($domain);
                         
                        logModuleCall("CF Partner", "Get single zone detail", $domain, (array)$getZonedetail);
                        if ($getZonedetail['result'] == 'error') {
                           $error = "Error Code: " . $getZonedetail['data']['cferrorcode'] . ", Error Message: " . $getZonedetail['data']['apierror'];
                        } elseif ($getZonedetail['result'][0]['id'] != '') {
                            $getzoneid = $getZonedetail['result'][0]['id'];
                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $getzoneid]);
                            $domainstatus = "Zone/Website added successfully";
                        } else {
                            $resolve_to = 'cloudflare-resolve-to.' . $domain;
                            $subdomains = 'www';
                            if ($zone_type == 'full') {
                                $zoneType = 'full_zone_set';
                            } elseif ($zone_type == 'partial') {
                                $zoneType = 'zone_set';
                            }

                            $hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
                            $zone_add = $hostAPI->zone_set($cf_user_key, $domain, $resolve_to, $subdomains, $zoneType);

                            logModuleCall("CF Partner", "Add zone (create account)", $domain, (array) $zone_add);

                            if ($zone_add->result == 'error') {
                                if ($zone_add->err_code == 219) {
                                    $error = "Domain/Website already exists with another user";
                                } else {
                                    $error = $zone_add->msg;
                                }
                            }
        
                            if ($zone_add->result == 'success') {
                                $zoneResult = $CF->getSingleZone($domain);
                               
                                logModuleCall("CF Partner", "get zone detail", ['zone' => $domain], $zoneResult);
                                $CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
                                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                                if ($zoneResult['result'][0]['name_servers'] != '') {
                                    $domainId = '';
                                    $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                    $domainId = $domainDetail->id;
                                    if ($domainId) {
                                        $CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId, $domain);
                                    }
                                }
                                if ($plan) {
                                    if ($plan == "FREE") {
                                        $planTag = 'Free';
                                    } elseif ($plan == "PARTNERS_PRO") {
                                        $planTag = 'CF_RESELLER_PRO';
                                    } elseif ($plan == "PARTNERS_BIZ") {
                                        $planTag = 'CF_RESELLER_BIZ';
                                    } elseif ($plan == "PARTNERS_ENT") {
                                        $planTag = 'CF_RESELLER_ENT';
                                    }
                                }
            
                                $domainstatus = $language['cf_website_added_success_domain'];
                             
                                if ($planTag != 'Free') {
                                    $plan_sub = $hostAPI->reseller_sub_new($cf_user_key, $domain, $planTag);
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
            
                                        // $results = localAPI($command, $postData, $adminUsername);
                                        // $success = $language['cf_website_added_success'];
                                        $domainstatus = $language['cf_website_added_success_domain'];
                                    } else {
                                        $error = $plan_sub->msg;
                                    }
                                }
                            }
                        }
                    }
                }
                $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount)", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount)", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
    
                $server_ip = $params['customfields']['dns_ip'] ? $params['customfields']['dns_ip'] : $ip;
                if ($server_ip) {
                    $dnsData = [
                        [
                            'zone_id' => $createZone['result']['id'],
                            'cfdnstype' => 'A',
                            'cfdnsname' => 'www',
                            'cfdnsvalue' => $server_ip,
                            'cfdnsttl' => '120',
                            'proxied' => ($proxy == '1') ? 'true' : 'false',
                        ],
                        [
                            'zone_id' => $createZone['result']['id'],
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
            
        } else {
            $error = $language['cf_domains_domain_req'];
        }
    }
    if (isset($_POST['deletedomain']) && $_POST['deletedomain'] == 'true') {
        if ($_POST['deletewebsite'] != '') {
            $serviceid = $params['serviceid'];
            $getZone = Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('zone', $whmcs->get_req_var('deletewebsite'))->where('sid', $serviceid)->first();
            $zoneid = $getZone->zoneid;
            if ($zoneid) {
                $del_zone = $CF->delete_zone($zoneid);
                logModuleCall('WGS CF  Provisioning Module', 'delete domain (terminate) - (clientarea)', ['zone' => $whmcs->get_req_var('deletewebsite'), 'zoneid' => $zoneid], $del_zone);
                if ($del_zone['success'] == 1) {
                    $delteZone = $CF->deleteZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $whmcs->get_req_var('deletewebsite')]);
                    $domainstatus = $language['cf_website_deleted_success'];
                } elseif ($del_zone['result'] == "error") {
                    $error = "Error Code: " . $del_zone['data']['cferrorcode'] . ", Error Message: " . $del_zone['data']['apierror'];
                }
            } else {
                $error = $language['cf_domains_zoneid_notfound'];
            }
        } else {
            $error = $language['cf_domains_zone_notfound'];
        }
    }
    if ($getSetting->servicetype == 'hosting_partner') {
        $totalRecords = $CF->getAllZones();
    } else {
        $getAllZonesData = $CF->getAllZones($accountId);
        $totalPage = $getAllZonesData['result_info']['total_pages'];
        $totalRecords = $getAllZonesData['result'];
        while ($totalPage > 1) {
            $getChunkZones = $CF->getAllZones($accountId, $totalPage);
            $totalRecords = array_merge($totalRecords, $getChunkZones['result']);
            $totalPage--;
        }
    }

    $zoneArr = [];
    if ((isset($totalRecords['result']) && $totalRecords['result'] == 'error') || $getAllZonesData['result'] == 'error') {
        $AllZonesData = $getAllZonesData['result'] == 'error' ? $getAllZonesData['result'] : $totalRecords['result'];
        $error = "Error Code: " . $AllZonesData['data']['cferrorcode'] . ", Error Message: " . $AllZonesData['data']['apierror'];
    } else {
        $totalUsedDomain = 0;
        foreach ($totalRecords as $zone) {
            if (Capsule::table('mod_cf_manage_domains')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->where('zone', $zone['name'])->count() > 0) {

                $default = ($zone['name'] == $defaultDomain) ? true : false;
                $zoneArr[] = ['zone_id' => $zone['id'], 'zonename' => $zone['name'], 'status' => $zone['status'], 'default' => $default, 'plan' => $zone['plan']['name']];
                $totalUsedDomain++;
            } else {
                if ($getSetting->servicetype == 'hosting_partner') {
                    if (isset ( $zone['id']) &&  $zone['id'] != "" && $zone['name'] != "") {
                        $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $zone['name'], 'zoneid' => $zone['id']]);
                    }
                    $zoneArr[] = ['zone_id' => $zone['id'], 'zonename' => $zone['name'], 'status' => $zone['status'], 'default' => true, 'plan' => $zone['plan']['name']];
                    $totalUsedDomain++;
                }
            }
        }
    }

    $noDomain = false;
    $numberOfDomains = ($numberOfDomains == 0) ? 1 : $numberOfDomains;
    if ($numberOfDomains == $totalUsedDomain) {
        $noDomain = true;
    }

    if ($numberOfDomains > 1) {
        if ($vars['login_error'] != '') {
            $result = array(
                'templatefile' => $templateFile,
                'breadcrumb' => '<a href="clientarea.php?action=productdetails&id=' . $params['serviceid'] . '</a>',
                'vars' => $vars,
            );
        } else {
            if ($vars['loginsuccess'] != '') {
                $success = $vars['loginsuccess'];
            }

            $result = array(
                'templatefile' => 'template/manage_domains',
                'vars' => array(
                    'wgs_lang' => $language,
                    'user_lang' => $clientLanguage,
                    'error' => $error,
                    'zones' => $zoneArr,
                    'serviceid' => $params['serviceid'],
                    'successmessage' => $success,
                    'domainmessage' => $domainstatus,
                    'nodomain' => $noDomain,
                    'totalUsedDomain' => $totalUsedDomain,
                    'totalAssignDomains' => $numberOfDomains,
                    'pro_plan_price' => $getSetting->pro_plan_price ? $getSetting->pro_plan_price : '$0.00',
                    'biz_plan_price' => $getSetting->biz_plan_price ? $getSetting->biz_plan_price : '$0.00'
                )
            );
        }
        return $result;
    } else {
        if ($errors || $zone_error) {
            $code = array(
                'templatefile' => $templateFile,
                'breadcrumb' => '<a href="clientarea.php?action=productdetails&id=' . $params['serviceid'] . '</a>',
                'vars' => $vars,
            );
            return $code;
        } else {
            $code = "<form method='post' id='cloudflareform' action='clientarea.php?action=productdetails&id=" . $params['serviceid'] . "'>";
            $code .= "<input type='hidden' name='modop' value='custom'>";
            $code .= "<input type='hidden' name='a' value='ManageCf'>";
            $code .= "<input type='hidden' name='cf_action' id='cf_action' value='manageWebsite'>";
            $code .= "<input type='hidden' name='website' id='website' value='" . $domain . "'>";
            $code .= "<input type='submit' id='manage_cf' value='" . $language['cf_manage_cf'] . "' class='btn'>";
            $code .= "</form>";
            return $code;
        }
    }
}

function wgs_cf_partnerapi_ClientAreaCustomButtonArray($params)
{
    $CF = new cloudflare();
    $clientLanguage = $CF->wgsCfGetClientLanguage($params);
    $language = $CF->wgsCfGetLang($clientLanguage);

    $buttonarray = array(
        $language['cf_manage_cf'] => "ManageCf",
    );
    return $buttonarray;
}

function wgs_cf_partnerapi_ManageCf($params)
{
    global $whmcs;
    global $CONFIG;
    $website = $whmcs->get_req_var("website");
    $CF = new cloudflare();
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

    if ($getSetting->servicetype == 'hosting_partner') {
        $emailAddress = $params['customfields']['CloudFlare Email Address'] ? $params['customfields']['CloudFlare Email Address'] : $params['customfields']['cf_email'];
        $cf_user_api_key = $params['customfields']['Cloudflare User API Key'] ? $params['customfields']['Cloudflare User API Key'] : $params['customfields']['cf_user_api_key'];

        $CF->ApiEmail = $emailAddress;
        $CF->ApiKey = $cf_user_api_key;
    }
    $getZoneDetail = $CF->getSingleZone($website);

    if ($getZoneDetail['result']['0'] != '')
        $zoneid = $getZoneDetail['result']['0']['id'];
    else
        $zoneid = $params['customfields']['zone_id'];

    if ($whmcs->get_req_var('cfaction') == 'dns' && isset($_POST['ajaxaction'])) {
        if ($zoneid == '') {
            echo "Error: Zone id is missing!";
        } else {
            $CF->zoneidentifier = $zoneid;
            $clientLanguage = $CF->wgsCfGetClientLanguage($params);
            $language = $CF->wgsCfGetLang($clientLanguage);

            include_once __DIR__ . '/includes/ajax.php';
        }
        exit();
    }
    if ($zoneid == '') {
        return "Error: Zone id is missing!";
    }
    $CF->zoneidentifier = $zoneid;
    $clientLanguage = $CF->wgsCfGetClientLanguage($params);
    $language = $CF->wgsCfGetLang($clientLanguage);

    $systemURL = $systemurl = $CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL'];

    $www = (strpos($_SERVER['HTTP_HOST'], 'www')) ? 1 : 0;

    if ($www) {
        if ($_SERVER['HTTPS'] != '' && $_SERVER['HTTPS'] == 'on') {
            $origin = 'https://www.';
        } else {
            $origin = 'http://www.';
        }

        $moduleURL = $url = str_replace('https://', $origin, $systemurl) . '/modules/servers/wgs_cf_partnerapi/';
    } else {
        $moduleURL = $url = $systemurl . '/modules/servers/wgs_cf_partnerapi/';
    }
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
    $vars = array(
        'url' => $getSetting->api_url,
        'wgs_lang' => $language,
        'user_lang' => $clientLanguage
    );
    global $whmcs;
    $apiurl = $getSetting->api_url;
    if (isset($website)) {
        $action = $whmcs->get_req_var("cf_action");
        switch ($action) {
            case 'manageWebsite':
                include_once __DIR__ . '/includes/manageWebsite.php';
                break;
        }
        return $pagearray;
    }
}
