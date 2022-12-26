<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

global $CONFIG, $customadminpath;
if (isset($_POST['generate_key']) && !empty($_POST['generate_key'])) {
    $service_provider = $whmcs->get_req_var('service_provider');
    $location = $whmcs->get_req_var('location');
    $applicationKey = $whmcs->get_req_var('application');
    $secretKey = $whmcs->get_req_var('secret');
    $accountEmail = $whmcs->get_req_var('account_number');

    $systemURL = $CONFIG['SystemURL'];
    if (!empty($CONFIG['SystemSSLURL']))
        $systemURL = $CONFIG['SystemSSLURL'];
    $adminName = !empty($customadminpath) ? $customadminpath : 'admin';
    if (!empty($applicationKey) && !empty($secretKey)) {
        if ($location == 'europe') {
            $apiLocation = 'https://eu.api.' . $service_provider . '.com/createApp/';
            $url = "https://eu.api." . $service_provider . ".com/1.0/auth/credential";
        } elseif ($location == 'canada') {
            $apiLocation = 'https://ca.api.' . $service_provider . '.com/createApp/';
            $url = "https://ca.api." . $service_provider . ".com/1.0/auth/credential";
        } elseif ($location == 'us') {
            $apiLocation = 'https://api.' . $service_provider . '.us/createApp/';
            $url = "https://api." . $service_provider . ".us/1.0/auth/credential";
        }
        $data = WGsVmwareVerfyApps($url, $applicationKey, $systemURL, $adminName, $applink, $accountEmail);
        $error = $data['status'];
        if (!empty($data['data']['message']))
            $error = $data['data']['message'];
        if (empty($error)) {
            $consumer_key = $data['data']['consumerKey'];
            if (!empty($consumer_key)) {
                $count = Capsule::table('mod_ovh_manage_apps')->where('account_number', $accountEmail)->count();
                if ($count == 0) {
                    $insertData = [
                        'secret_key' => encrypt($secretKey),
                        'consumer_key' => encrypt($consumer_key),
                        'location' => $apiLocation,
                        'application_key' => encrypt($applicationKey),
                        'service_location' => $location,
                        'api_service_provider' => $service_provider,
                        'account_number' => $accountEmail,
                        'status' => 'pending',
                    ];
                    Capsule::table('mod_ovh_manage_apps')->insert($insertData);
                    header("location:" . $data['data']['validationUrl']);
                }
            }
        }
    } else {
        $error = 'These fields "Application Key, Secret Key" are required.';
    }
} elseif (!empty($verifyId)) {
    $accountData = Capsule::table('mod_ovh_manage_apps')->where('id', $verifyId)->first();
    $service_provider = $accountData->api_service_provider;
    $location = $accountData->service_location;
    $applicationKey = decrypt($accountData->application_key);
    $secretKey = decrypt($accountData->secret_key);
    $accountEmail = $accountData->account_number;
    $consumer_key = decrypt($accountData->consumer_key);

    $systemURL = $CONFIG['SystemURL'];
    if (!empty($CONFIG['SystemSSLURL']))
        $systemURL = $CONFIG['SystemSSLURL'];
    $adminName = !empty($customadminpath) ? $customadminpath : 'admin';
    if (!empty($applicationKey) && !empty($secretKey)) {
        if (!empty($applicationKey) && !empty($secretKey)) {
            if ($location == 'europe') {
                $apiLocation = 'https://eu.api.' . $service_provider . '.com/createApp/';
                $url = "https://eu.api." . $service_provider . ".com/1.0/auth/credential";
            } elseif ($location == 'canada') {
                $apiLocation = 'https://ca.api.' . $service_provider . '.com/createApp/';
                $url = "https://ca.api." . $service_provider . ".com/1.0/auth/credential";
            } elseif ($location == 'us') {
                $apiLocation = 'https://api.' . $service_provider . '.us/createApp/';
                $url = "https://api." . $service_provider . ".us/1.0/auth/credential";
            }
        }
        $data = WGsVmwareVerfyApps($url, $applicationKey, $systemURL, $adminName, $applink, $accountEmail);
        $error = $data['status'];
        if (!empty($data['data']['message']))
            $error = $data['data']['message'];
        if (empty($error)) {
            $consumer_key = $data['data']['consumerKey'];
            if (!empty($consumer_key)) {
                $count = Capsule::table('mod_ovh_manage_apps')->where('id', $verifyId)->count();
                if ($count != 0) {
                    $updateData = [
                        'consumer_key' => encrypt($consumer_key),
                    ];
                    Capsule::table('mod_ovh_manage_apps')->where('id', $verifyId)->update($updateData);
                    header("location:" . $data['data']['validationUrl']);
                }
            }
        }
    } else {
        $error = 'These fields "Application Key, Secret Key" are required.';
    }
}

function WGsVmwareVerfyApps($url, $applicationKey, $systemURL, $adminName, $applink, $accountEmail) {
    $redirectUrl = $systemURL . "/" . $adminName . "/" . $applink . "&tab=key_setup&account_number=" . $accountEmail . "&success=true";
    $json = '{
                    "accessRules": [
                        {
                            "method": "GET",
                            "path": "/*"
                        },
                        {
                            "method": "POST",
                            "path": "/*"
                        },
                        {
                            "method": "PUT",
                            "path": "/*"
                        },
                        {
                            "method": "DELETE",
                            "path": "/*"
                        }
                    ],
                    "redirection":"' . $redirectUrl . '"
		 }';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-Ovh-Application:' . $applicationKey
    ));
    $result = curl_exec($ch);
    $error = '';
    if (curl_error($ch))
        $error = "Curl Failed.";
    curl_close($ch);
    $data = json_decode($result, true);
    return ['data' => $data, 'status' => $error];
}

?>
