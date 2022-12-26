<?php
use WGS\MODULES\CLOUDFLARE\wgs_cloudflare as cloudflare;
use WHMCS\Database\Capsule;
use WHMCS\View\Menu\Item as MenuItem;

if (file_exists(__DIR__ . '/classes/class.php'))
    include_once __DIR__ . '/classes/class.php';

ob_start();
function hook_cloudflare_add_zone($vars)
{
    $addon_id = $vars["addonid"];
    $service_id = $vars["serviceid"];
    $user_id = $vars["userid"];

    $addonData = Capsule::table('mod_cf_p_addons')->where('addonid', $addon_id)->first();
    //    $getPid = Capsule::table('tblproducts')->where('configoption6', $addonData->cf_plan)->first();
    $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('status', '1')->where('plan', $addonData->cf_plan)->first();
 
    if (count($productSetting) > 0) {
        $pid = $productSetting->product_id;
        $getProductIDetail = Capsule::table('tblproducts')->where('id', $pid)->where('servertype', 'wgs_cf_partnerapi')->first();

        $params = (array) $getProductIDetail;
        $CF = new cloudflare();
        $serviceDetail = $CF->wgs_cf_get_serviceDomain($service_id);

        $pid = $params['pid'] = $params['id'];

        $plan = $productSetting->plan;
        $member = $productSetting->member;
        $account_type = $productSetting->member_type;
        $create_cf_user = $productSetting->user;
        $user_accountid = $productSetting->accountid;
        $zone_type = $productSetting->zone_type;
        $ip = $productSetting->dns_ip;
        $status = $productSetting->status;
        $proxy = $productSetting->proxy;

        $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();

        $domain = $params['domain'] = $serviceDetail['domain'];
        $alreadyExist = Capsule::table('tblhosting')->where('packageid', $pid)->where('domain', $domain)->count();
        $ratePlanId = $addonData->cf_plan;
        $ratePublic_name = "FREE";
        if ($ratePlanId == 'PARTNERS_PRO') {
            $ratePublic_name = "PRO";
        } elseif ($ratePlanId == 'PARTNERS_BIZ') {
            $ratePublic_name = "Business";
        } elseif ($ratePlanId == 'PARTNERS_ENT') {
            $ratePublic_name = "Enterprise";
        }
        $getUserDetail = Capsule::table('tblclients')->where('id', $user_id)->first();

        $params['clientsdetails']['email'] = $getUserDetail->email;
        $params['userid'] = $getUserDetail->id;

        if ($addon_id && $alreadyExist == 0) {
            $customFieldId = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%cloudflare_domain%')->first();

            $command = 'AddOrder';
            $postData = array(
                'clientid' => $user_id,
                'pid' => array($pid),
                'domain' => array($domain),
                'billingcycle' => array('free'),
                'noemail' => true,
                'noinvoice' => true,
                'noinvoiceemail' => true,
                'paymentmethod' => $serviceDetail['paymentmethod']
            );
            if (count($customFieldId) > 0)
                $postData = array_merge($postData, ['customfields' => array(base64_encode(serialize(array($customFieldId->id => $domain))))]);

            $serviceResults = $CF->wgsCfWhmcsLocalApi($command, $postData);

            if ($serviceResults['result'] == 'success') {
                try {
                    if (Capsule::table('mod_cf_manage_service')->where('sid', $serviceResults['productids'])->count() == 0) {
                        Capsule::table('mod_cf_manage_service')->insert(['sid' => $serviceResults['productids'], 'addonid' => $addon_id]);
                    } else {
                        Capsule::table('mod_cf_manage_service')->where('sid', $serviceResults['productids'])->update(['addonid' => $addon_id]);
                    }
                    $serviceid = $params['serviceid'] = $serviceResults['productids'];
                    $command = 'AcceptOrder';
                    $postData = array(
                        'orderid' => $serviceResults['orderid'],
                        'autosetup' => false,
                        'sendemail' => false,
                    );
                    $acceptServiceResults = $CF->wgsCfWhmcsLocalApi($command, $postData);
                    if ($acceptServiceResults['result'] == 'error') {
                        logActivity("Accept Cloudflare client service {$serviceResults['productids']} after addon activate failed {$acceptServiceResults['message']}");
                    } else {



                        if ($getSetting->servicetype != 'hosting_partner') {
                            $name = $getUserDetail->email;
                            if ($create_cf_user != 0) {
                                $getCfUserId = Capsule::table('mod_cf_manage_users')->where('uid', $user_id)->first();
                                $accountId = $getCfUserId->cf_uid;

                                if ($accountId == '') {
                                    $create_user = $CF->create_user($name, $account_type);
                                    if ($create_user['success'] == 1) {
                                        $accountId = $create_user['result']['id'];
                                        //                                $CF->updateCustomFieldValues('user_account_id', $serviceid, $accountId, $params['pid']);
                                        $CF->saveUserId(['uid' => $user_id, 'cf_uid' => $accountId]);
                                    } else {
                                        logActivity("On Addon activate create CF user failed: " . $create_user['data']['cferrorcode'] . ", Error Message: " . $create_user['data']['apierror']);
                                    }
                                }
                            } else {
                                $accountId = $user_accountid;
                            }
                            if ($accountId) {
                                if ($member == '1' && $create_cf_user != 0) {
                                    $getrole = $CF->get_roles($accountId);
                                    $roleID = $getrole['result'][0]['id'];
                                    if ($roleID) {
                                        $createMember = $CF->add_member($accountId, $roleID, $params);
                                        if ($createMember['result'] == 'error') {
                                            $result = "Add Member failed on hook. Error Code: " . $createMember['data']['cferrorcode'] . ", Error Message " . $createMember['data']['apierror'];
                                            logActivity("On Addon activate create CF member failed: {$result}");
                                        } else {
                                            $memberId = $createMember['result']['id'];
                                            $CF->updateCustomFieldValues('member_id', $serviceid, $memberId, $params['pid']);
                                        }
                                    }
                                }
                                $createZone = $CF->create_zone($domain, $accountId, $zone_type, $serviceid);
                                if ($createZone['result'] == 'error') {
                                    $result = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message " . $createZone['data']['apierror'];
                                    logActivity("On Addon activate create CF zone {$domain} failed: {$result}");
                                } else {
                                    $CF->updateCustomFieldValues('cloudflare_domain', $serviceid, $domain, $params['pid']);
                                    $CF->updateCustomFieldValues('zone_id', $serviceid, $createZone['result']['id'], $params['pid']);
                                    $zoneSubId = '';
                                    if ($plan != 'FREE') {
                                        $createZoneSub = $CF->create_zone_subscription_onhook($domain, $createZone['result']['id'], $ratePlanId, $ratePublic_name);
                                        if ($createZoneSub['result'] == 'error') {
                                            $result = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message " . $createZone['data']['apierror'];
                                        } else {
                                            $zoneSubId = $createZoneSub['result']['id'];
                                            //$CF->updateCustomFieldValues('zone_sub_id', $serviceid, $zoneSubId, $params['pid']);
                                            $result = 'success';
                                        }
                                    } else {
                                        //$CF->updateCustomFieldValues('zone_sub_id', $serviceid, '', $params['pid']);
                                    }
                                    $CF->saveZone(['uid' => $user_id, 'sid' => $serviceid, 'zone' => $domain, 'zoneid' => $createZone['result']['id'], 'sub_id' => $zoneSubId]);

                                    if ($createZone['result']['name_servers'] != '') {
                                        $domainId = '';
                                        $doaminDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                        $domainId = $doaminDetail->id;
                                        if ($domainId)
                                            $CF->updateNS($createZone['result']['name_servers'], $domainId, $domain);
                                    }

                                    $CF->zoneidentifier = $createZone['result']['id'];
                                }
                            }
                        } else {
                            if (!class_exists('CF_HOST_API')) {
                                require(__DIR__ . '/classes/class.cloudflareAPI.php');
                            }
                            $cfUserdetail = Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $params['clientsdetails']['email'])->first();
                            if ($cfUserdetail) {
                                $emailAddress = $cfUserdetail->email;
                                $cfpassword = $password = $cfUserdetail->password;
                                $username = $cfUserdetail->username;
                            }
                            if ($emailAddress == '') {
                                $emailAddress = $params['clientsdetails']['email'];
                            }
                            if ($username == '') {
                                $username = $params['clientsdetails']['firstname'].$params['serviceid'];
                                //$username = str_replace('@', '', $emailAddress);
                            }
                            if ($cfpassword == '') {
                                $cfpassword = $CF->wgs_cf_generateRandomString(10);
                            }
                            $hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
                            $request_body = array("cloudflare_email" => $emailAddress, "cloudflare_pass" => $cfpassword, "cloudflare_username" => $username);
                            $result = $hostAPI->user_create($emailAddress, $cfpassword, $username);
                            logModuleCall("CF Partner", "create CF user on product addon activate", $request_body, (array) $result);
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
                                    if (count($cfUserdetail) == 0) {
                                        Capsule::table('mod_cf_zone')->insert(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
                                    } else {
                                        Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $emailAddress)->update(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
                                    }
                                } catch (Exception $ex) {
                                    logActivity("Unable to insert/update table: mod_cf_zone error: {$ex->getMessage()}");
                                }

                                logModuleCall("CF Partner", "WHMCS API updateclientproduct, Clodflare User Created on product addon activated", $values, $results);
                                $resolve_to = 'cloudflare-resolve-to.' . $domain;
                                $subdomains = 'www';
                                if ($zone_type == 'full')
                                    $zoneType = 'full_zone_set';
                                elseif ($zone_type == 'partial') {
                                    $zoneType = 'zone_set';
                                }
                                $zone_add = $hostAPI->zone_set($result->response->user_key, $domain, $resolve_to, $subdomains, $zoneType);

                                logModuleCall("CF Partner", "Add zone (create account)", $domain, (array) $zone_add);
                                if ($zone_add->result == 'error') {
                                    logActivity("Zone create failed on product addon activation: {$zone_add->msg}");
                                } elseif ($zone_add->result == 'success') {
                                    $CF->ApiEmail = $result->response->cloudflare_email;
                                    $CF->ApiKey = $result->response->user_api_key;
                                    $zoneResult = $CF->getSingleZone($domain);
                                    logModuleCall("CF Partner", "get zone detail on product addon activation", ['zone' => $domain], $zoneResult);
                                    $CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
                                    $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                                    if ($zoneResult['result'][0]['name_servers'] != '') {
                                        $domainId = '';
                                        $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                        $domainId = $domainDetail->id;
                                        if ($domainId)
                                            $CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId, $domain);
                                    }

                                    $planTag = 'Free';
                                    if ($ratePlanId == "PARTNERS_PRO")
                                        $planTag = 'CF_RESELLER_PRO';
                                    elseif ($ratePlanId == "PARTNERS_BIZ")
                                        $planTag = 'CF_RESELLER_BIZ';
                                    elseif ($ratePlanId == "PARTNERS_ENT")
                                        $planTag = 'CF_RESELLER_ENT';

                                    if ($planTag != 'Free') {
                                        $plan_sub = $hostAPI1->reseller_sub_new($result->response->user_key, $domain, $planTag);
                                        logModuleCall("CF Partner", "Add sub plan to Cloudflare Partner on product addon activation", array('zone' => $domain, 'plan_tag' => $planTag), (array) $plan_sub);
                                        if ($plan_sub->result == "success") {
                                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
                                        } else {
                                            logActivity("Zone create subscription failed on product addon activation: {$plan_sub->msg}");
                                        }
                                    }
                                }
                            }
                        }


                        $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                        logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount) on product addon activation", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                        $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                        logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount) on product addon activation", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
                        $server_ip = $ip;
                        if ($server_ip) {
                            $dnsData = [
                                [
                                    'zone_id' => $zoneResult['result'][0]['id'],
                                    'cfdnstype' => 'A',
                                    'cfdnsname' => 'www',
                                    'cfdnsvalue' => $server_ip,
                                    'cfdnsttl' => '120',
                                    'proxied' => ($proxy == '1') ? 'true' : 'false',
                                ],
                                [
                                    'zone_id' => $zoneResult['result'][0]['id'],
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
                                    logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) on product addon activation", $dnsVal, 'Error: ' . $createDns['data']['apierror']);
                                    logActivity("Set DNS record failed on product addon activation $domain: " . $createDns['data']['apierror']);
                                } else {
                                    logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) on product addon activation", $dnsVal, 'Dns record has been successfully added');
                                    logActivity("DNS record has been steup zone on product addon activation :$domain");
                                }
                            }
                        }
                    }
                } catch (Exception $ex) {
                    logActivity("Update/Insert data in mod_cf_manage_service: {$ex->getMessage()}");
                }
            } else {
                logActivity("Create Cloudflare client service after addon ativate failed {$serviceResults['message']}");
            }
        }
    }
}

add_hook('AddonActivation', 1, 'hook_cloudflare_add_zone');
add_hook('AddonActivated', 1, 'hook_cloudflare_add_zone');


add_hook('AfterRegistrarRegistration', 1, function ($vars) {

    $domainId = $vars['params']['domainid'];
    $domain = $vars['params']['sld'] . '.' . $vars['params']['tld'];

    $getCFProducts = Capsule::table('tblproducts')->where('servertype', 'wgs_cf_partnerapi')->get();
    $pidArr = [];
    foreach ($getCFProducts as $product) {
        $pidArr[] = $product->id;
    }
    $getHostingData = Capsule::table('tblhosting')->whereIn('packageid', $pidArr)->where('domain', $domain)->first();

    $pid = $getHostingData->packageid;
    $getProductIDetail = Capsule::table('tblproducts')->where('id', $pid)->where('servertype', 'wgs_cf_partnerapi')->first();

    if (count($getProductIDetail) > 0) {
        $command = 'ModuleCreate';
        $postData = array(
            'serviceid' => $getHostingData->id
        );
        $CF = new cloudflare();
        $acceptServiceResults = $CF->wgsCfWhmcsLocalApi($command, $postData);
        if ($acceptServiceResults['result'] == 'error') {
            logActivity("Module create API after domain registration falied {$acceptServiceResults['message']}");
        } else {
            logActivity("Module create API after domain registration successfuly run");
        }
    } else {
        // $getHostingData = Capsule::table('tblhosting')->whereNotIn('packageid', $pidArr)->where('domain', $domain)->first();

        $getHostingData = Capsule::table('tblhosting')->whereNotIn('packageid', $pidArr)->where('domain', $domain)->first();
        $getAddonId = Capsule::table('tblhostingaddons')->where('hostingid', $getHostingData->id)->get();
        foreach ($getAddonId as $addonid) {
            $AddonIdArr[] = $addonid->addonid;
        }
		   
		if(count($AddonIdArr) > 0){

            $getCfPlan = Capsule::table('mod_cf_p_addons')->whereIn('addonid', $AddonIdArr)->first();
            $CfPlan = $getCfPlan->cf_plan;

            $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('status', '1')->where('plan', $getCfPlan->cf_plan)->first();

            $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
            if (count($productSetting) > 0) {
                $plan = $productSetting->plan;
                $member = $productSetting->member;
                $account_type = $productSetting->member_type;
                $zone_type = $productSetting->zone_type;
                $ip = $productSetting->dns_ip;
                $status = $productSetting->status;
                $proxy = $productSetting->proxy;
                $create_cf_user = $productSetting->user;
                $user_accountid = $productSetting->accountid;
                //        $getPid = Capsule::table('tblproducts')->where('configoption6', $CfPlan)->first();
                $pid = $productSetting->product_id;

                $getProductIDetail = Capsule::table('tblproducts')->where('id', $pid)->where('servertype', 'wgs_cf_partnerapi')->first();

                $sid = $params['serviceid'] = $serviceid = $getHostingData->id;
                $uid = $getHostingData->userid;
                $command = 'GetClientsProducts';
                $postData = array(
                    'clientid' => $uid,
                    'serviceid' => $sid,
                );

                $CF = new cloudflare();
                $serviceResults = $CF->wgsCfWhmcsLocalApi($command, $postData);

                if ($getProductIDetail->servertype == 'wgs_cf_partnerapi') {
                    $params = (array) $getProductIDetail;
                    $params['pid'] = $params['id'];
                    $params['clientid'] = $params['userid'] = $serviceResults['clientid'];
                    $params['serviceid'] = $serviceResults['serviceid'];
                    $domainCfId = $CF->getCustomFieldId('cloudflare_domain', $pid);
                    $zoneCFId = $CF->getCustomFieldId('zone_id', $pid);
                    //                $userAccountCFid = $CF->getCustomFieldId('user_account_id', $pid);
                    $memberCFId = $CF->getCustomFieldId('member_id', $pid);
                    $zoneSubCFId = $CF->getCustomFieldId('zone_sub_id', $pid);
                    $subdomanIpCFId = $CF->getCustomFieldId('dns_ip', $pid);
                    $emailAddressFieldId = $CF->getCustomfieldId('cf_email|CloudFlare Email Address', $pid);
                    $passwordFieldId = $CF->getCustomfieldId('cf_password|CloudFlare Password', $pid);
                    $usernameFieldId = $CF->getCustomfieldId('cf_username|CloudFlare Username', $pid);
                    $userkeyfieldId = $CF->getCustomfieldId('cf_user_key|Cloudflare User Key', $pid);
                    $userAPIkeyfieldId = $CF->getCustomfieldId('cf_user_api_key|Cloudflare User API Key', $pid);

                    foreach ($serviceResults['products']['product'][0]['customfields']['customfield'] as $customfields) {
                        if ($customfields['id'] == $domainCfId)
                            $domain = $customfields['value'];
                        if ($customfields['id'] == $zoneCFId)
                            $zone_id = $customfields['value'];
                        //                    if ($customfields['id'] == $userAccountCFid)
                        //                        $account_id = $customfields['value'];
                        if ($customfields['id'] == $memberCFId)
                            $member_id = $customfields['value'];
                        if ($customfields['id'] == $zoneSubCFId)
                            $sub_id = $customfields['value'];
                        if ($customfields['id'] == $subdomanIpCFId)
                            $server_ip = $customfields['value'];
                        if ($customfields['id'] == $emailAddressFieldId)
                            $email = $customfields['value'];
                        if ($customfields['id'] == $userkeyfieldId)
                            $userKey = $customfields['value'];
                        if ($customfields['id'] == $userAPIkeyfieldId)
                            $userApiKey = $customfields['value'];
                    }

                    $getCfUserId = Capsule::table('mod_cf_manage_users')->where('uid', $uid)->first();
                    $accountId = $getCfUserId->cf_uid;
                    if ($server_ip == '')
                        $server_ip = $ip;
                    $website = $domain;
                    $getZoneId = Capsule::table('mod_cf_manage_domains')->where('uid', $params['clientid'])->where('sid', $params['serviceid'])->where('zone', $domain)->first();
                    $zone_id = $getZoneId->zoneid;
                    if ($zone_id == '') {
                        // $getAddonId = Capsule::table('mod_cf_manage_service')->where('sid', $params['serviceid'])->first();
                        // $addonData = Capsule::table('mod_cf_p_addons')->where('addonid', $getAddonId->addonid)->first();
                        $ratePlanId = $CfPlan; //$addonData->cf_plan;
                        $ratePublic_name = "FREE";
                        if ($ratePlanId == 'PARTNERS_PRO') {
                            $ratePublic_name = "PRO";
                        } elseif ($ratePlanId == 'PARTNERS_BIZ') {
                            $ratePublic_name = "Business";
                        } elseif ($ratePlanId == 'PARTNERS_ENT') {
                            $ratePublic_name = "Enterprise";
                        }
                        $getUserDetail = Capsule::table('tblclients')->where('id', $uid)->first();

                        $params['clientsdetails']['email'] = $getUserDetail->email;
                        if ($getSetting->servicetype != 'hosting_partner') {
                            $zoneResult = $CF->getAllZones($website);
                            if ($zoneResult['result'] == '' && $zoneResult['success'] == 1) {

                                $name = $getUserDetail->firstname . $serviceid;
                                if ($create_cf_user != 0) {
                                    if ($accountId == '') {
                                        $create_user = $CF->create_user($name, $account_type);
                                        if ($create_user['success'] == 1) {
                                            $accountId = $create_user['result']['id'];
                                            //                                $CF->updateCustomFieldValues('user_account_id', $serviceid, $accountId, $params['pid']);
                                            $CF->saveUserId(['uid' => $uid, 'cf_uid' => $accountId]);
                                        } else {
                                            logActivity("On domain register create CF user failed: " . $create_user['data']['cferrorcode'] . ", Error Message: " . $create_user['data']['apierror']);
                                        }
                                    }
                                } else {
                                    $accountId = $user_accountid;
                                }

                                if ($accountId) {
                                    if ($member == '1' && $create_cf_user != 0) {
                                        $getrole = $CF->get_roles($accountId);
                                        $roleID = $getrole['result'][0]['id'];
                                        if ($roleID && $member_id == '') {
                                            $createMember = $CF->add_member($accountId, $roleID, $params);
                                            if ($createMember['result'] == 'error') {
                                                $result = "Error Code: " . $createMember['data']['cferrorcode'] . ", Error Message " . $createMember['data']['apierror'];
                                                logActivity("On Addon activate create CF member failed: {$result}");
                                            } else {
                                                $memberId = $createMember['result']['id'];
                                                $CF->updateCustomFieldValues('member_id', $serviceid, $memberId, $params['pid']);
                                            }
                                        }
                                    }
                                    $createZone = $CF->create_zone($domain, $accountId, $zone_type, $serviceid);
                                    if ($createZone['result'] == 'error') {
                                        $result = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message " . $createZone['data']['apierror'];
                                        logActivity("On Addon activate create CF zone {$domain} failed: {$result}");
                                    } else {
                                        $CF->saveZone(['uid' => $uid, 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id']]);
                                        $CF->updateCustomFieldValues('cloudflare_domain', $serviceid, $domain, $params['pid']);
                                        $CF->updateCustomFieldValues('zone_id', $serviceid, $createZone['result']['id'], $params['pid']);
                                        if ($createZone['result']['name_servers'] != '') {
                                            $domainId = '';
                                            $doaminDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                            $domainId = $doaminDetail->id;
                                            if ($domainId)
                                                $CF->updateNS($createZone['result']['name_servers'], $domainId);
                                        }
                                        $CF->zoneidentifier = $createZone['result']['id'];
                                        $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                                        logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount)", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                                        $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                                        logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount)", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
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
                                        if ($plan != 'FREE') {
                                            $createZoneSub = $CF->create_zone_subscription_onhook($domain, $createZone['result']['id'], $ratePlanId, $ratePublic_name);
                                            if ($createZoneSub['result'] == 'error') {
                                                $result = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message " . $createZone['data']['apierror'];
                                            } else {
                                                $zoneSubId = $createZoneSub['result']['id'];
                                                $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id'], 'sub_id' => $zoneSubId]);
                                                //                                            $CF->updateCustomFieldValues('zone_sub_id', $serviceid, $zoneSubId, $params['pid']);
                                                $result = 'success';
                                            }
                                        } else {
                                            //                                        $CF->updateCustomFieldValues('zone_sub_id', $serviceid, '', $params['pid']);
                                        }
                                    }
                                }
                            } else {
                                $nameservers = $zoneResult['result'][0]['name_servers'];
                                if ($nameservers) {
                                    if ($domainId) {
                                        $CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId);
                                    }
                                    $CF->zoneidentifier = $zoneResult['result'][0]['id'];
                                    $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                                    logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount)", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                                    $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                                    logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount)", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
                                    if ($server_ip) {
                                        $dnsData = [
                                            [
                                                'zone_id' => $zoneResult['result'][0]['id'],
                                                'cfdnstype' => 'A',
                                                'cfdnsname' => 'www',
                                                'cfdnsvalue' => $server_ip,
                                                'cfdnsttl' => '120',
                                                'proxied' => ($proxy == '1') ? 'true' : 'false',
                                            ],
                                            [
                                                'zone_id' => $zoneResult['result'][0]['id'],
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
                                    if ($plan != 'FREE') {
                                        $createZoneSub = $CF->create_zone_subscription_onhook($domain, $zoneResult['result'][0]['id'], $ratePlanId, $ratePublic_name);
                                        if ($createZoneSub['result'] == 'error') {
                                            $result = "Error Code: " . $createZone['data']['cferrorcode'] . ", Error Message " . $createZone['data']['apierror'];
                                        } else {
                                            $zoneSubId = $createZoneSub['result']['id'];
                                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $createZone['result']['id'], 'sub_id' => $zoneSubId]);
                                            //                                        $CF->updateCustomFieldValues('zone_sub_id', $serviceid, $zoneSubId, $params['pid']);
                                            $result = 'success';
                                        }
                                    } else {
                                        $CF->updateCustomFieldValues('zone_sub_id', $serviceid, '', $params['pid']);
                                    }
                                }
                            }
                        } else {
                            if (!class_exists('CF_HOST_API')) {
                                require(__DIR__ . '/classes/class.cloudflareAPI.php');
                            }
                            $cfUserdetail = Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $params['clientsdetails']['email'])->first();
                            if ($cfUserdetail) {
                                $emailAddress = $cfUserdetail->email;
                                $cfpassword = $password = $cfUserdetail->password;
                                $username = $cfUserdetail->username;
                            }
                            if ($emailAddress == '') {
                                $emailAddress = $params['clientsdetails']['email'];
                            }
                            if ($username == '') {
                                $username = $params['clientsdetails']['firstname'].$params['serviceid'];
                                //$username = str_replace('@', '', $emailAddress);
                            }
                            if ($cfpassword == '') {
                                $cfpassword = $CF->wgs_cf_generateRandomString(10);
                            }
                            if ($getSetting->hosting_apikey != '') {
                                $hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
                                if ($email & $userApiKey) {
                                    $CF->ApiEmail = $email;
                                    $CF->ApiKey = $userApiKey;
                                    $zoneResult = $CF->getSingleZone($domain);
                                    logModuleCall("CF Partner", "get zone detail on after domain register hook", ['zone' => $domain], $zoneResult);
                                    $CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
                                }
                                if (!isset($zoneResult['result'][0]['id']) && $zoneResult['result'][0]['id'] == '') {
                                    $request_body = array("cloudflare_email" => $emailAddress, "cloudflare_pass" => $cfpassword, "cloudflare_username" => $username);
                                    $result = $hostAPI->user_create($emailAddress, $cfpassword, $username);
                                    logModuleCall("CF Partner", "create CF user on domain register", $request_body, (array) $result);
                                    if ($result->result == "success") {
                                        $command = "updateclientproduct";
                                        $adminuser = '';
                                        $values["serviceid"] = $params["serviceid"];
                                        $values['domain'] = $domain;
                                        $values["customfields"] = base64_encode(serialize(array($emailAddressFieldId => $result->response->cloudflare_email, $passwordFieldId => $cfpassword, $usernameFieldId => $result->response->cloudflare_username, $userkeyfieldId => $result->response->user_key, $userAPIkeyfieldId => $result->response->user_api_key)));
                                        $results = localAPI($command, $values, $adminuser);
                                        try {
                                            if (count($cfUserdetail) == 0) {
                                                Capsule::table('mod_cf_zone')->insert(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
                                            } else {
                                                Capsule::table('mod_cf_zone')->where('uid', $params['userid'])->where('email', $emailAddress)->update(['uid' => $params['userid'], 'email' => $emailAddress, 'password' => $cfpassword, 'username' => $username]);
                                            }
                                        } catch (Exception $ex) {
                                            logActivity("Unable to insert/update table: mod_cf_zone error: {$ex->getMessage()}");
                                        }

                                        logModuleCall("CF Partner", "WHMCS API updateclientproduct, Clodflare User Created on after domain register hook", $values, $results);
                                        $resolve_to = 'cloudflare-resolve-to.' . $domain;
                                        $subdomains = 'www';
                                        if ($zone_type == 'full')
                                            $zoneType = 'full_zone_set';
                                        elseif ($zone_type == 'partial') {
                                            $zoneType = 'zone_set';
                                        }
                                        $zone_add = $hostAPI->zone_set($result->response->user_key, $domain, $resolve_to, $subdomains, $zoneType);

                                        logModuleCall("CF Partner", "Add zone (create account)", $domain, (array) $zone_add);
                                        if ($zone_add->result == 'error') {
                                            logActivity("Zone create failed on after domain register hook: {$zone_add->msg}");
                                        } elseif ($zone_add->result == 'success') {
                                            $CF->ApiEmail = $result->response->cloudflare_email;
                                            $CF->ApiKey = $result->response->user_api_key;
                                            $zoneResult = $CF->getSingleZone($domain);
                                            logModuleCall("CF Partner", "get zone detail on after domain register hook", ['zone' => $domain], $zoneResult);
                                            $CF->zoneidentifier = $zoneid = $zoneResult['result'][0]['id'];
                                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                                            if ($zoneResult['result'][0]['name_servers'] != '') {
                                                $domainId = '';
                                                $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                                $domainId = $domainDetail->id;
                                                if ($domainId)
                                                    $CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId, $domain);
                                            }
                                            $planTag = 'Free';
                                            if ($ratePlanId == "PARTNERS_PRO")
                                                $planTag = 'CF_RESELLER_PRO';
                                            elseif ($ratePlanId == "PARTNERS_BIZ")
                                                $planTag = 'CF_RESELLER_BIZ';
                                            elseif ($ratePlanId == "PARTNERS_ENT")
                                                $planTag = 'CF_RESELLER_ENT';
                                            if ($planTag != 'Free') {
                                                $plan_sub = $hostAPI1->reseller_sub_new($result->response->user_key, $domain, $planTag);
                                                logModuleCall("CF Partner", "Add sub plan to Cloudflare Partner on after domain register hook", array('zone' => $domain, 'plan_tag' => $planTag), (array) $plan_sub);
                                                if ($plan_sub->result == "success") {
                                                    $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
                                                } else {
                                                    logActivity("Zone create subscription failed on after domain register hook: {$plan_sub->msg}");
                                                }
                                            }
                                            $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                                            logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount) on after domain register hook", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                                            $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                                            logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount) on after domain register hook", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
                                            $server_ip = $ip;
                                            if ($server_ip) {
                                                $dnsData = [
                                                    [
                                                        'zone_id' => $zoneResult['result'][0]['id'],
                                                        'cfdnstype' => 'A',
                                                        'cfdnsname' => 'www',
                                                        'cfdnsvalue' => $server_ip,
                                                        'cfdnsttl' => '120',
                                                        'proxied' => ($proxy == '1') ? 'true' : 'false',
                                                    ],
                                                    [
                                                        'zone_id' => $zoneResult['result'][0]['id'],
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
                                                        logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) on after domain register hook", $dnsVal, 'Error: ' . $createDns['data']['apierror']);
                                                        logActivity("Set DNS record failed on after domain register hook $domain: " . $createDns['data']['apierror']);
                                                    } else {
                                                        logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) on after domain register hook", $dnsVal, 'Dns record has been successfully added');
                                                        logActivity("DNS record has been steup zone on after domain register hook :$domain");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid]);
                                    if ($zoneResult['result'][0]['name_servers'] != '') {
                                        $domainId = '';
                                        $domainDetail = Capsule::table('tbldomains')->where('domain', $domain)->first();
                                        $domainId = $domainDetail->id;
                                        if ($domainId)
                                            $CF->updateNS($zoneResult['result'][0]['name_servers'], $domainId, $domain);
                                    }
                                    $planTag = 'Free';
                                    if ($ratePlanId == "PARTNERS_PRO")
                                        $planTag = 'CF_RESELLER_PRO';
                                    elseif ($ratePlanId == "PARTNERS_BIZ")
                                        $planTag = 'CF_RESELLER_BIZ';
                                    elseif ($ratePlanId == "PARTNERS_ENT")
                                        $planTag = 'CF_RESELLER_ENT';
                                    if ($planTag != 'Free') {
                                        $plan_sub = $hostAPI1->reseller_sub_new($result->response->user_key, $domain, $planTag);
                                        logModuleCall("CF Partner", "Add sub plan to Cloudflare Partner n after domain register hook", array('zone' => $domain, 'plan_tag' => $planTag), (array) $plan_sub);
                                        if ($plan_sub->result == "success") {
                                            $CF->saveZone(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'zone' => $domain, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
                                        } else {
                                            logActivity("Zone create subscription failed n after domain register hook: {$plan_sub->msg}");
                                        }
                                    }
                                    $always_httpResult = $CF->changeAlwaysUseTttpsSetting('on');
                                    logModuleCall("WGS CF Reseller", "Enable always http (add website on createaccount) n after domain register hook", ['domain' => $domain, 'value' => 'on'], $always_httpResult);
                                    $autoMinify = $CF->changeMinifySetting('on', 'on', 'on');
                                    logModuleCall("WGS CF Reseller", "Auto Minify (add website on createaccount) n after domain register hook", ['JavaScript' => 'on', 'CSS' => 'on', 'HTML' => 'on'], $autoMinify);
                                    $server_ip = $ip;
                                    if ($server_ip) {
                                        $dnsData = [
                                            [
                                                'zone_id' => $zoneResult['result'][0]['id'],
                                                'cfdnstype' => 'A',
                                                'cfdnsname' => 'www',
                                                'cfdnsvalue' => $server_ip,
                                                'cfdnsttl' => '120',
                                                'proxied' => ($proxy == '1') ? 'true' : 'false',
                                            ],
                                            [
                                                'zone_id' => $zoneResult['result'][0]['id'],
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
                                                logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) n after domain register hook", $dnsVal, 'Error: ' . $createDns['data']['apierror']);
                                                logActivity("Set DNS record failed on product addon activation $domain: " . $createDns['data']['apierror']);
                                            } else {
                                                logModuleCall("WGS CF Reseller", "Set DNS record (add website on createaccount) n after domain register hook", $dnsVal, 'Dns record has been successfully added');
                                                logActivity("DNS record has been steup zone n after domain register hook :$domain");
                                            }
                                        }
                                    }
                                }
                            } else {
                                logActivity("CF partner host API key missing on after domain register hook :$domain");
                            }
                        }
                    }
                }
            }
	   }	
    }
});

add_hook('ClientAreaPrimarySidebar', 1, function (MenuItem $primarySidebar) {
    if (isset($_GET['action']) && $_GET['action'] == 'productdetails' && $_GET['id'] != '') {
        global $smarty;
        $hostingService = Capsule::table('tblhosting')->where('id', $_GET['id'])->first();
        $getProductIDetail = Capsule::table('tblproducts')->where('servertype', 'wgs_cf_partnerapi')->where('id', $hostingService->packageid)->first();

        if ($getProductIDetail) {
            $cfUrl = 'clientarea.php?action=productdetails&id=' . $hostingService->id . '&modop=custom&a=ManageCf&cf_action=manageWebsite&website=' . $hostingService->domain;

            if (!is_null($primarySidebar->getChild('Service Details Actions'))) {
                if (!is_null($primarySidebar->getChild('Service Details Actions')->getChild('Custom Module Button Manage Cloudflare')))
                    $primarySidebar->getChild('Service Details Actions')->getChild('Custom Module Button Manage Cloudflare')->setUri($cfUrl)->setClass('cloudflare-menu-btn');
            }
        }
    }
});

add_hook('ClientAreaPage', 1, function ($vars) {
    if (isset($_GET['a']) && $_GET['a'] == 'confproduct') {
        $i = $_REQUEST['i'];
        $productID = $vars['productinfo']['pid'];
        $domain = $vars['domain'];

        $customfieldsArr = [];
        foreach ($vars['customfields'] as $key => $customfield) {
            if ($customfield['textid'] == 'domain') {
                if ($vars['customfields'][$key]['value'] != '')
                    $domain = $vars['customfields'][$key]['value'];
                $vars['customfields'][$key]['input'] = '<input type="text" name="customfield[' . $vars['customfields'][$key]['id'] . ']" id="customfield' . $vars['customfields'][$key]['id'] . '" value="' . $domain . '" size="30" class="form-control">';
                $customfieldsArr[] = $vars['customfields'][$key];
            } elseif ($customfield['textid'] == 'dnsip') {
                $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('status', '1')->where('product_id', $productID)->first();
                $ip = '';
                if (count($productSetting) > 0)
                    $ip = $productSetting->dns_ip;
                if ($vars['customfields'][$key]['value'] != '')
                    $ip = $vars['customfields'][$key]['value'];
                $vars['customfields'][$key]['input'] = ($ip) ? '<input type="text" name="customfield[' . $vars['customfields'][$key]['id'] . ']" id="customfield' . $vars['customfields'][$key]['id'] . '" value="' . $ip . '" size="30" class="form-control">' : '<input type="text" name="customfield[' . $vars['customfields'][$key]['id'] . ']" id="customfield' . $vars['customfields'][$key]['id'] . '" value="" size="30" class="form-control">';
                $customfieldsArr[] = $vars['customfields'][$key];
            } else {
                $customfieldsArr[] = $vars['customfields'][$key];
            }
        }
        return ['customfields' => $customfieldsArr];
    }
});

add_hook('InvoicePaid', 1, function ($vars) {
    $upgradeInvoiceDetail = Capsule::table("mod_cf_upgarde_plans")->where('invoiceid', $vars['invoiceid'])->first();
    $uid = $upgradeInvoiceDetail->uid;
    $sid = $upgradeInvoiceDetail->sid;
    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
    $domainDetail = Capsule::table('mod_cf_manage_domains')->where('zone', $upgradeInvoiceDetail->zone)->first();
    if ($domainDetail) {
        $command = 'GetClientsProducts';
        $postData = array(
            'serviceid' => $sid,
        );
        $adminUsername = '';

        $results = localAPI($command, $postData, $adminUsername);

        $zoneid = $domainDetail->zoneid;
        $domain = $domainDetail->zone;

        if ($results['result'] == "success") {
            $productSetting = Capsule::table('mod_cloudflare__reseller_productsettings')->where('product_id', $results['products']['product'][0]['pid'])->first();
            $plan = $productSetting->plan;
            $status = $productSetting->status;

            $CF = new cloudflare();
            if ($getSetting->servicetype == 'hosting_partner') {
                if (!class_exists('CF_HOST_API')) {
                    require(__DIR__ . '/classes/class.cloudflareAPI.php');
                }
                $hostAPI = new CF_HOST_API(decrypt($getSetting->hosting_apikey));
                if ($upgradeInvoiceDetail->plan == "Pro Plan")
                    $planTag = 'CF_RESELLER_PRO';
                elseif ($plan == "Buisness Plan")
                    $planTag = 'CF_RESELLER_BIZ';
                $getCfEmailCustomFiledId = $CF->getCustomFieldId("cf_email|CloudFlare Email Address", $results['products']['product'][0]['pid']);
                $getCfuserApiKeyCustomFiledId = $CF->getCustomFieldId("cf_user_api_key|Cloudflare User API Key", $results['products']['product'][0]['pid']);
                $getCfuserKeyCustomFiledId = $CF->getCustomFieldId("cf_user_key|Cloudflare User Key", $results['products']['product'][0]['pid']);
                foreach ($results['products']['product'][0]['customfields']['customfield'] as $customfield) {
                    if ($customfield['id'] == $getCfEmailCustomFiledId)
                        $email = $customfield['value'];
                    if ($customfield['id'] == $getCfuserApiKeyCustomFiledId)
                        $cf_user_api_key = $customfield['value'];
                    if ($customfield['id'] == $getCfuserKeyCustomFiledId)
                        $userkey = $customfield['value'];
                }

                if ($domainDetail->sub_id != '') {
                    $oldSubscriptionId = $domainDetail->sub_id;
                    $oldSubscriptionLabel = $domainDetail->sub_label;
                    $cancelSub = $hostAPI->reseller_sub_cancel($userkey, $upgradeInvoiceDetail->zone, $oldSubscriptionLabel, $oldSubscriptionId);
                    logModuleCall("CF Partner", "Upgrade plan (cancel existing subscription)", array('zone' => $upgradeInvoiceDetail->zon, 'plan_tag' => $planTag), (array) $cancelSub);
                } else {
                    $cancelSub->result = 'success';
                }
                if ($cancelSub->result == 'success') {
                    $plan_sub = $hostAPI->reseller_sub_new($userkey, $upgradeInvoiceDetail->zone, $planTag);
                    logModuleCall("CF Partner", "Upgrade plan", array('zone' => $upgradeInvoiceDetail->zone, 'plan_tag' => $planTag), (array) $plan_sub);
                    if ($plan_sub->result == "success") {
                        logActivity("Plan upgraded successfully for domain :{$domain}");
                        $CF->saveZone(['uid' => $results['products']['product'][0]['clientid'], 'sid' => $results['products']['product'][0]['id'], 'zone' => $upgradeInvoiceDetail->zone, 'zoneid' => $zoneid, 'sub_id' => $plan_sub->response->sub_id, 'sub_label' => $planTag]);
                    }
                }
            } else {
                $subscriptionsid = $domainDetail->sub_id;
                $domain = $domainDetail->zone;
                $update_zone_sub = $CF->update_zone_subscription($zoneid, $domain, $plan);
                if ($update_zone_sub['result'] == 'error') {
                    logActivity("Plan upgrade failed for domain :{$domain}. Error Code: " . $update_zone_sub['data']['cferrorcode'] . ", Error Message: " . $update_zone_sub['data']['apierror']);
                } else {
                    $zoneSubId = $update_zone_sub['result']['id'];
                    $CF->saveZone(['uid' => $results['products']['product'][0]['clientid'], 'sid' => $results['products']['product'][0]['id'], 'zone' => $upgradeInvoiceDetail->zone, 'zoneid' => $zoneid, 'sub_id' => $zoneSubId, 'sub_label' => '']);

                    logActivity("Plan upgraded successfully for domain :{$domain}");
                }
            }
        } else {
            logActivity("Plan upgrade failed for domain :{$domain}. Error Message: " . $results['message']);
        }
    }
});


#add domain after domain register for every domain
add_hook('AfterRegistrarRegistration', 2, function($vars) {

    $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
      
        if($getSetting->domain_registrar == 'on'){

            $userId = $vars['params']['userid'];
            $domainName = strtolower($vars['params']['domainname']);
            $domainId = $vars['params']['domainid'];

            $productId = Capsule::table('mod_cloudflare__reseller_productsettings')->where('plan','FREE')->value('product_id');
             
            $checkDomainExists = Capsule::table('tblhosting')->where('packageid', $productId)->where('domain', $domainName)->count();
            
            if($checkDomainExists == 0){
                $paymentMethod = Capsule::table('tbldomains')->where('id',$domainId)->value('paymentmethod');
            
                $postData = array(
                    'clientid' => $userId,
                    'pid' => array($productId),
                    'domain' => array($domainName),
                    'billingcycle' => array('free'),
                    'noemail' => true,
                    'noinvoice' => true,
                    'noinvoiceemail' => true,
                    'paymentmethod' =>  $paymentMethod
                );

                $customFieldId = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $productId)->where('fieldname', 'like', '%cloudflare_domain%')->value('id');
                 
                if (count($customFieldId) > 0)
                    $postData = array_merge($postData, ['customfields' => array(base64_encode(serialize(array($customFieldId => $domainName))))]);
               
                 
                $command = 'AddOrder';
                $adminUsername = ''; // Optional for WHMCS 7.2 and later

                $results = localAPI($command, $postData, $adminUsername);
                 
                if($results['result'] == 'success'){

                    $orderId = $results['orderid'];
                    $command = 'AcceptOrder';
                    $postData = array(
                        'orderid' => $orderId,
                    );

                    $results = localAPI($command, $postData, $adminUsername);
                }
            }else{
                logactivity ("CF add order:  Error Message: Your domain (".$domainName.") already exists in Cloudflare with user/other user!");
            } 
        }
});

// // Add sidebar In active domain

add_hook('ClientAreaPrimarySidebar', 2, function (MenuItem $primarySidebar) {

    global $whmcs;
    $action = $whmcs->get_req_var("action"); 
    $filename = APP::getCurrentFileName();
     
    if ($action == 'domaindetails' && $filename == 'clientarea') {
        $getSetting = Capsule::table('mod_cloudflare__reseller_settings')->first();
      
        if($getSetting->domain_registrar == 'on'){
             
            $client = Menu::context("client");

            $clientid = (int) $client->id;
            $domainId = $whmcs->get_req_var('id');
            $getDomainName = Capsule::table('tbldomains')->where('id',$domainId)->first();
            $domainName = strtolower($getDomainName->domain);
            $domainStatus = $getDomainName->status;
            $serviceId = Capsule::table('tblhosting')->where('domain',$domainName)->where('userid',$clientid)->value('id');
             
            if (isset($domainId) && $domainId != '') {

                if ($domainStatus == 'Active') {
                    $DomainDetails = $primarySidebar->getChild("Domain Details Management");

                    $DomainDetails->addChild('Manage CF DNS', array(
                        'label' =>  'Manage DNS Records', //'Manage DNS Records',
                        'uri' => 'clientarea.php?action=productdetails&id=' . $serviceId,
                        'order' => '100',
                    ));
                }
            }
        }
    }
});


// clean the output buffer

ob_end_flush();