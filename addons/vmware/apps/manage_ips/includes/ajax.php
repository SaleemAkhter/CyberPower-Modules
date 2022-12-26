<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

global $whmcs;
if (isset($_POST['customaction'])) {
    switch ($_POST['customaction']) {
        case 'imap_test_conn':
            $account = $whmcs->get_req_var('userAccount');
            $port = $whmcs->get_req_var('portnumber');
            $host = $whmcs->get_req_var('hostname');
            $username = $whmcs->get_req_var('username');
            $password = $whmcs->get_req_var('password');
            $ssltype = $whmcs->get_req_var('ssltype');
            $language = $whmcs->get_req_var('language');
            $error = '';

            if (!$host)
                $error .= '<li>Servername is required!</li>';
            if (!$ssltype)
                $error .= '<li>SSL type is required!</li>';
            if (!$username)
                $error .= '<li>Username is required!</li>';
            if (!$password)
                $error .= '<li>Password is required!</li>';

            if (!empty($error)) {
                print json_encode(['status' => 'error', 'msg' => '<div class="errorbox"><ul>' . $error . '</ul></div>']);
                exit();
            }

            if (!empty($port)) {
                $port = $port;
            } else {
                $port = 143;
            }

            if ($ssltype == 'tls' || $ssltype == 'default') {
                $layer = 'tls';
            } else {
                $layer = 'ssl';
            }
            $certvalidate = 'novalidate-cert';
            $hostname = '{' . $host . ':' . $port . '/imap/' . $layer . '/' . $certvalidate . '/norsh}INBOX';
            $username = trim($username);

            if ($password == '******') {
                $queryimapsetting = Capsule::table('mod_ovh_imap')->where('account_user', $account)->first();
                $password = $queryimapsetting->soyouimappass;
                $decodedpass = decrypt($password);
            } else {
                $password = trim($password);
                $decodedpass = $password;
            }
            $conn = imap_open($hostname, $username, $decodedpass);
            if (!$conn) {
                print json_encode(['status' => 'error', 'msg' => '<div class="errorbox">Imap Error : ' . imap_last_error() . '</div>']);
            }
            if ($conn) {
                print json_encode(['status' => 'success', 'msg' => '<font color="green" class="connected">Connected</font>']);
            }
            exit();
            break;

        default:
            break;
    }
}
exit();
?>