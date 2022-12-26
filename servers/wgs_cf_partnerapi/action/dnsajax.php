<?php

use WGS\MODULES\CLOUDFLARE\wgs_cloudflare as cloudflare;

if (file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/init.php'))
    include_once dirname(dirname(dirname(dirname(__DIR__)))) . '/init.php';

if (file_exists(__DIR__ . '/classes/class.php'))
    include_once __DIR__ . '/classes/class.php';

if (isset($_REQUEST["dnsaction"])) {

//    $apiurl = $_REQUEST["apiurl"];
//    $cfusername = dnsajaxcfdecrypt($_REQUEST["cfusername"]);
//    $cfapikey = dnsajaxcfdecrypt($_REQUEST["cfapikey"]);

    $CF = new cloudflare();

//    if (isset($_REQUEST["ajaxaction"])) {
//        $data = $_REQUEST["serializedformdata"];
//        $formdata = array();
//        parse_str($data, $formdata); # Parse query string
//        $zoneid = $formdata['zone_id'];
//    } else {
//        $zoneid = (!empty($_REQUEST['zoneid'])) ? $_REQUEST['zoneid'] : $_REQUEST['zone_id'];
//    }

    $zoneid = (!empty($_REQUEST['zoneid'])) ? $_REQUEST['zoneid'] : $_REQUEST['zone_id'];
    $zoneid = dnsajaxcfdecrypt($zoneid);
    $CF->zoneidentifier = $zoneid;

    $language = $CF->wgsCfGetLang($_POST['user_lang']);

    $salt = base64_decode($_REQUEST["salt"]);

    $wgs_cf_partnerapi_ajax = $CF;

    $dnsaction = $_REQUEST["ajaxaction"];

    switch ($dnsaction) {
        case "adddnsrecord":
            if (isset($_REQUEST["dnstype"])) {

                $dnsttlvalues = $wgs_cf_partnerapi_ajax->dnsttlvalues($language);
                $ttldisabled = "";

                $dnsrecordtype = $_REQUEST["dnstype"];
                $zoneid = $_REQUEST["zone_id"];
                $html = '<input type="hidden" name="dnsaction" value="adddns">';
                $html .= '<input type="hidden" name="zone_id" value="' . $zoneid . '">';
                switch ($dnsrecordtype) {
                    case "A":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_a_record_text'] . '</p>';
                        break;
                    case "AAAA":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_aaaa_record_text'] . '</p>';
                        break;
                    case "CNAME":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_cname_record_text'] . '</p>';
                        break;
                    case "MX":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_mx_record_text'] . '</p>';
                        break;
                    case "TXT":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_txt_record_text'] . '</p>';
                        break;
                    case "SPF":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_spf_record_text'] . '</p>';
                        break;
                    case "NS":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_ns_record_text'] . '</p>';
                        break;
                }
                $html .= '<div class="form-group"><label for="cfdnstype">' . $language['cf_dns_type'] . ':</label><input type="text" class="form-control" id="cfdnstype" name="cfdnstype" value="' . $dnsrecordtype . '" readonly></div>';
                $html .= '<div class="form-group"><label for="cfdnsname">' . $language['cf_dns_name'] . ':</label><input type="text" class="form-control" id="cfdnsname" name="cfdnsname" value=""></div>';
                switch ($dnsrecordtype) {
                    case "A":
                    case "AAAA":
                    case "CNAME":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_target'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
                        $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                                . '<select class="form-control" id="proxied" name="proxied">'
                                . '<option value="true">' . $language['cf_dns_proxied_on'] . '</option>'
                                . '<option value="false">' . $language['cf_dns_proxied_off'] . '</option>'
                                . '</select>'
                                . '</div>';
                        break;
                    case "NS":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_content'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
                        break;
                    case 'MX':
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_mailserver'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
                        $html .= '<div class="form-group"><label for="cfmxpriority">' . $language['cf_dns_priority'] . ':</label><input type="text" class="form-control" id="cfmxpriority" name="cfmxpriority" value=""><small>0-65355</small></div>';
                        break;
                    case "SPF":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_content'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="" placeholder="v=spf..."></div>';
                    case "TXT":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_content'] . ':</label><textarea class="form-control" id="cfdnsvalue" name="cfdnsvalue"></textarea></div>';
                        break;
                }
                $html .= '<div class="form-group"><label for="cfdnsttl">' . $language['cf_dns_ttl'] . ':</label>'
                        . '<select class="form-control" name="cfdnsttl">';
                foreach ($dnsttlvalues as $key => $value) {
                    $html .= '<option value="' . $key . '">' . $value . '</option>';
                }
                $html .= '</select>';
                if (!empty($ttldisabled)) {
                    $html .= ' ' . $language['cf_dns_ajax_ttl_manage'];
                }
                $html .= '</div>';
                print $html;
            }
            break;
        case "editdnsrecord":
            if (isset($_REQUEST["apiurl"]) && isset($_REQUEST["cfusername"]) && isset($_REQUEST["cfapikey"]) && isset($_REQUEST["salt"])) {

                $dnsttlvalues = $wgs_cf_partnerapi_ajax->dnsttlvalues($language);

                $dnsid = $_REQUEST["dnsid"];

                $dnsdetails = $wgs_cf_partnerapi_ajax->dnsRecordDetails($dnsid);

                $ttldisabled = "";
                $trueSelect = "";
                $falseSelect = "";

                if ($dnsdetails["result"]["proxied"]) {
                    $ttldisabled = "disabled";
                }
                if ($dnsdetails["result"]["proxiable"] == false) {
                    $proxiable = "false";
                } elseif ($dnsdetails["result"]["proxiable"] == true) {
                    $proxiable = "true";
                }

                if ($dnsdetails["result"]["proxied"] == false) {
                    $proxied = "false";
                    $falseSelect = 'selected="selected"';
                } elseif ($dnsdetails["result"]["proxied"] == true) {
                    $proxied = "true";
                    $trueSelect = 'selected="selected"';
                }

                if ($dnsdetails["result"]["locked"] == false) {
                    $locked = "false";
                } elseif ($dnsdetails["result"]["locked"] == true) {
                    $locked = "true";
                }

                $html = '<input type="hidden" name="dnsaction" value="editdns">'
                        . '<input type="hidden" name="dnsrecordid" value="' . $dnsdetails["result"]["id"] . '">'
                        . '<input type="hidden" name="proxiable" value="' . $proxiable . '">'
                        // . '<input type="hidden" name="proxied" value="' . $proxied . '">'
                        . '<input type="hidden" name="locked" value="' . $locked . '">'
                        . '<input type="hidden" name="zone_id" value="' . $_REQUEST["zoneid"] . '">'
                        . '<input type="hidden" name="zone_name" value="' . $dnsdetails["result"]["zone_name"] . '">';
                $html .= '<div class="form-group"><label for="cfdnstype">' . $language['cf_dns_type'] . ':</label><input type="text" class="form-control" id="cfdnstype" name="cfdnstype" value="' . $dnsdetails["result"]["type"] . '" readonly></div>';
                $html .= '<div class="form-group"><label for="cfdnsname">' . $language['cf_dns_name'] . ':</label><input type="text" class="form-control" id="cfdnsname" name="cfdnsname" value="' . $dnsdetails["result"]["name"] . '"></div>';
                if ($dnsdetails["result"]["type"] == "A" || $dnsdetails["result"]["type"] == "AAAA" || $dnsdetails["result"]["type"] == "CNAME") {
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_value'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                            . '<select class="form-control" name="proxied">'
                            . '<option value="true" ' . $trueSelect . '>' . $language['cf_dns_proxied_on'] . '</option>'
                            . '<option value="false" ' . $falseSelect . '>' . $language['cf_dns_proxied_off'] . '</option>'
                            . '</select>'
                            . '</div>';
                }
                if ($dnsdetails["result"]["type"] == "TXT") {
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_value'] . ':</label><textarea class="form-control"  name="cfdnsvalue">' . $dnsdetails["result"]["content"] . '</textarea></div>';
                }
                if ($dnsdetails["result"]["type"] == "MX") {
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_server'] . ':</label><textarea class="form-control"  name="cfdnsvalue">' . $dnsdetails["result"]["content"] . '</textarea></div>';
                    $html .= '<div class="form-group"><label for="cfmxpriority">' . $language['cf_dns_priority'] . ':</label><textarea class="form-control"  name="cfmxpriority">' . $dnsdetails["result"]["priority"] . '</textarea></div>';
                }
                $html .= '<div class="form-group"><label for="cfdnsttl">' . $language['cf_dns_ttl'] . ':</label>'
                        . '<select name="cfdnsttl" class="form-control" id="cfdnsttl" ' . $ttldisabled . '>';
                foreach ($dnsttlvalues as $key => $value) {
                    $html .= '<option value="' . $key . '">' . $value . '</option>';
                }
                $html .= '</select>';
                if (!empty($ttldisabled)) {
                    $html .= ' ' . $language['cf_dns_ajax_ttl_manage'];
                }
                $html .= '</div>';

                print $html;
            }
            break;
        case "enabledisabledns":
            $dnsttlvalues = $wgs_cf_partnerapi_ajax->dnsttlvalues($language);

            $dnsid = $_REQUEST["dnsid"];

            $dnsdetails = $wgs_cf_partnerapi_ajax->dnsRecordDetails($dnsid);

            if (!empty($_REQUEST['proxied']) && $_REQUEST['proxied'] == 1) {
                $proxied = "false";
                $proxiedSts = 1;
                $status = $language['cf_dns_ajax_disable_dns'];
            } else {
                $proxied = "true";
                $status = $language['cf_dns_ajax_enable_dns'];
                $proxiedSts = '';
            }

            if ($dnsdetails['result']['proxiable'] == 1) {
                $proxiable = "true";
            } else {
                $proxiable = "false";
            }

            if ($dnsdetails['result']['locked'] == 1) {
                $locked = "true";
            } else {
                $locked = "false";
            }

            $dnsrecordid = $dnsdetails['result']['id'];
            $ttl = $dnsdetails['result']['ttl'];
            $zone_id = $dnsdetails['result']['zone_id'];
            $zone_name = $dnsdetails['result']['zone_name'];
            $cfdnstype = $dnsdetails['result']['type'];
            $cfdnsname = $dnsdetails['result']['name'];
            $cfdnsvalue = $dnsdetails['result']['content'];

            $data = array(
                'dnsrecordid' => $dnsrecordid,
                'proxiable' => $proxiable,
                'proxied' => $proxied,
                'locked' => $locked,
                'cfdnsttl' => $ttl,
                'zone_id' => $zone_id,
                'zone_name' => $zone_name,
                'cfdnstype' => $cfdnstype,
                'cfdnsname' => $cfdnsname,
                'cfdnsvalue' => $cfdnsvalue,
            );
            $result = $wgs_cf_partnerapi_ajax->enableDisableDnsRecord($data);
            if ($result["success"]) {
                print "S_" . $cfdnstype . " " . $language['cf_dns_ajax_dns'] . " " . $status . " " . $language['cf_dns_ajax_dns_success'] . " _" . $proxiedSts;
            }
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if (!empty($result["data"]["error"])) {
                    $error .= $result["data"]["error"] . ". ";
                }
                if (!empty($result["data"]["apierror"])) {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                print "E_" . $error . "_" . $_REQUEST['proxied'];
            }
            break;
        case "dnsrecordpost":
//            $data = $_REQUEST["serializedformdata"];
//            $formdata = array();
//            parse_str($data, $formdata); # Parse query string
//            define('customparmas', serialize($_POST['serializedformdata']));
//            $strArray = explode("&", $_POST['serializedformdata']);
//            foreach ($strArray as $item) {
//                $array = explode("=", $item);
//                $returndata[] = $array;
//            }
            $formdata = $_POST;
            switch ($formdata["dnsaction"]) {
                case "adddns":
                    $result = $wgs_cf_partnerapi_ajax->createDNSRecord($formdata);
                    if (isset($result["success"])) {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_add_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if (!empty($result["data"]["error"])) {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if (!empty($result["data"]["apierror"])) {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
                case "editdns":
                    $data = $_REQUEST["serializedformdata"];
                    $formdata = array();
                    parse_str($data, $formdata); # Parse query string      
                    $result = $wgs_cf_partnerapi_ajax->editDNSRecord($formdata);
                    //    print_r($wgs_cf_partnerapi_ajax);
                    if (!empty($result["success"])) {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_update_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if (!empty($result["data"]["error"])) {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if (!empty($result["data"]["apierror"])) {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
                case "deletedns":
                    $data = $_REQUEST["serializedformdata"];
                    $formdata = array();
                    parse_str($data, $formdata); # Parse query string
                    $result = $wgs_cf_partnerapi_ajax->deleteDNSRecord($formdata);
                    if ($result["success"]) {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_delete_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if (!empty($result["data"]["error"])) {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if (!empty($result["data"]["apierror"])) {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
            }
            break;
    }
}

function dnsajaxcfdecrypt($encrypted) {
    // $key ='';
    $data = base64_decode($encrypted);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($data, 0, $ivlen);
    $hmac = substr($data, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($data, $ivlen + $sha2len);
    $decrypted = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac)) {
        return $decrypted;
    }
}

function dnsajaxcfencrypt($encrypted) {
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $encrypted = base64_encode($iv . $hmac . $ciphertext_raw);
    return $encrypted;
}

function xyz($strfromAjaxPOST) {
    $array = "";
    $returndata = "";
    $strArray = explode("&", $strfromPOST);
    $i = 0;
    foreach ($strArray as $str) {
        $array = explode("=", $str);
        $returndata[$i] = $array[0];
        $i = $i + 1;
        $returndata[$i] = $array[1];
        $i = $i + 1;
    }
    return $returndata;
}

?>
