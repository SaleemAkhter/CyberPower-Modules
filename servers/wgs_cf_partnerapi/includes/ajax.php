<?php

if (isset($_REQUEST["ajaxaction"])) {
    $dnsaction = $_REQUEST["ajaxaction"];
    switch ($dnsaction) {
        case "adddnsrecord":
            if (isset($_REQUEST["dnstype"])) {

                $dnsttlvalues = $CF->dnsttlvalues($language);
                $ttldisabled = "";

                $dnsrecordtype = $_REQUEST["dnstype"];
                $zoneid = $_REQUEST["zone_id"];
                $html = '<input type="hidden" name="dnsaction" value="adddns">';
                $html .= '<input type="hidden" name="zone_id" value="' . $zoneid . '">';
                $disabled = "";
                switch ($dnsrecordtype) {
                    case "A":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_a_record_text'] . '</p>';
                        $disabled = 'disabled="disabled"';
                        break;
                    case "AAAA":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_aaaa_record_text'] . '</p>';
                        $disabled = 'disabled="disabled"';
                        break;
                    case "CNAME":
                        $html .= '<p class = "adddnstext">' . $language['cf_dns_cname_record_text'] . '</p>';
                        $disabled = 'disabled="disabled"';
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
                $html .= '<div class="form-group"><label for="cfdnsname">' . $language['cf_dns_name'] . ':</label><input type="text" class="form-control" id="cfdnsname" name="cfdnsname" value="" placeholder="Use @ for root"></div>';
                
                switch ($dnsrecordtype) {
                    case "A":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_a_record_ip4'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
                        $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                            . '<select class="form-control" id="proxied" name="proxied">'
                            . '<option value="true">' . $language['cf_dns_proxied_on'] . '</option>'
                            . '<option value="false">' . $language['cf_dns_proxied_off'] . '</option>'
                            . '</select>'
                            . '</div>';
                        break;
                    case "AAAA":
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_a_record_ip6'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
                        $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                            . '<select class="form-control" id="proxied" name="proxied">'
                            . '<option value="true">' . $language['cf_dns_proxied_on'] . '</option>'
                            . '<option value="false">' . $language['cf_dns_proxied_off'] . '</option>'
                            . '</select>'
                            . '</div>';
                        break;
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
                        $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_ns'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value=""></div>';
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
                    . '<select class="form-control" id="cfdnsttl" name="cfdnsttl" ' . $disabled . '>';
                foreach ($dnsttlvalues as $key => $value) {
                    $html .= '<option value="' . $key . '">' . $value . '</option>';
                }

                $html .= '</select>';
                if ($ttldisabled != '') {
                    $html .= ' ' . $language['cf_dns_ajax_ttl_manage'];
                }

                $html .= '</div>';
                $script = '<script>jQuery(document).ready(function(){'
                    . 'jQuery("#proxied").change(function(){'
                    . 'if(jQuery(this).val() == "false"){'
                    . 'jQuery("#cfdnsttl").attr("disabled", false);'
                    . '}else{'
                    . 'jQuery("#cfdnsttl").attr("disabled", true);'
                    . '}'
                    . '});'
                    . '});</script>';
                print $html . $script;
            }
            break;
        case "dnsrecordpost":
            $dnsaction = $_REQUEST["dnsaction"];
            switch ($dnsaction) {
                case "adddns":
                    $formdata = $_POST;
                    $result = $CF->createDNSRecord($formdata);
                    if (isset($result["success"])) {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_add_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if ($result["data"]["error"] != '') {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if ($result["data"]["apierror"] != '') {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
                case "editdns":
                    $formdata = $_POST;
                    $result = $CF->editDNSRecord($formdata);
                    if ($result["success"] != '') {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_update_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if ($result["data"]["error"] != '') {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if ($result["data"]["apierror"] != '') {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
                case "deletedns":
                    $formdata = $_POST;
                    $result = $CF->deleteDNSRecord($formdata);
                    if ($result["success"]) {
                        print "S_" . $formdata["cfdnstype"] . " " . $language['cf_dns_ajax_delete_dns_success'];
                    }
                    if ($result['result'] == "error") {
                        $error = 'Error(' . $result["data"]["info"] . '): ';
                        if ($result["data"]["error"] != '') {
                            $error .= $result["data"]["error"] . ". ";
                        }
                        if ($result["data"]["apierror"] != '') {
                            $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                        }
                        print "E_" . $error;
                    }
                    break;
            }
            break;
        case "enabledisabledns":
            $dnsid = $_REQUEST["dnsid"];

            $dnsdetails = $CF->dnsRecordDetails($dnsid);

            if ($_REQUEST['proxied'] != '' && $_REQUEST['proxied'] == 1) {
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

            $result = $CF->enableDisableDnsRecord($data);
            if ($result["success"]) {
                print "S_" . $cfdnstype . " " . $language['cf_dns_ajax_dns'] . " " . $status . " " . $language['cf_dns_ajax_dns_success'] . " _" . $proxiedSts;
            }
            if ($result['result'] == "error") {
                $error = 'Error(' . $result["data"]["info"] . '): ';
                if ($result["data"]["error"] != '') {
                    $error .= $result["data"]["error"] . ". ";
                }
                if ($result["data"]["apierror"] != '') {
                    $error .= ' cfError(' . $result["data"]["cferrorcode"] . '):' . $result["data"]["apierror"];
                }
                print "E_" . $error . "_" . $_REQUEST['proxied'];
            }

            break;
        case "editdnsrecord":
            $dnsttlvalues = $CF->dnsttlvalues($language);

            $dnsid = $_REQUEST["dnsid"];

            $dnsdetails = $CF->dnsRecordDetails($dnsid);

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

            $disabled = "";
            $dnsrecordtype = $dnsdetails["result"]["type"];
            switch ($dnsrecordtype) {
                case "A":
                    $html .= '<p class = "adddnstext">' . $language['cf_dns_a_record_text'] . '</p>';
                    $disabled = 'disabled="disabled"';
                    break;
                case "AAAA":
                    $html .= '<p class = "adddnstext">' . $language['cf_dns_aaaa_record_text'] . '</p>';
                    $disabled = 'disabled="disabled"';
                    break;
                case "CNAME":
                    $html .= '<p class = "adddnstext">' . $language['cf_dns_cname_record_text'] . '</p>';
                    $disabled = 'disabled="disabled"';
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

            $html .= '<div class="form-group"><label for="cfdnstype">' . $language['cf_dns_type'] . ':</label><input type="text" class="form-control" id="cfdnstype" name="cfdnstype" value="' . $dnsdetails["result"]["type"] . '" readonly></div>';
            $html .= '<div class="form-group"><label for="cfdnsname">' . $language['cf_dns_name'] . ':</label><input type="text" class="form-control" id="cfdnsname" name="cfdnsname" placeholder="Use @ for root" value="' . $dnsdetails["result"]["name"] . '"></div>';

            switch ($dnsrecordtype) {
                case "A":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_a_record_ip4'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                        . '<select class="form-control" id="proxied" name="proxied">'
                        . '<option value="true" ' . $trueSelect . '>' . $language['cf_dns_proxied_on'] . '</option>'
                        . '<option value="false"' . $falseSelect . ' >' . $language['cf_dns_proxied_off'] . '</option>'
                        . '</select>'
                        . '</div>';
                    break;
                case "AAAA":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_a_record_ip6'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                        . '<select class="form-control" id="proxied" name="proxied">'
                        . '<option value="true" ' . $trueSelect . '>' . $language['cf_dns_proxied_on'] . '</option>'
                        . '<option value="false"' . $falseSelect . ' >' . $language['cf_dns_proxied_off'] . '</option>'
                        . '</select>'
                        . '</div>';
                    break;
                case "CNAME":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_target'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
                        . '<select class="form-control" id="proxied" name="proxied">'
                        . '<option value="true" ' . $trueSelect . '>' . $language['cf_dns_proxied_on'] . '</option>'
                        . '<option value="false" ' . $trueSelect . '>' . $language['cf_dns_proxied_off'] . '</option>'
                        . '</select>'
                        . '</div>';
                    break;
                case "NS":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_ns'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    break;
                case 'MX':
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_mailserver'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
                    $html .= '<div class="form-group"><label for="cfmxpriority">' . $language['cf_dns_priority'] . ':</label><input type="text" class="form-control" id="cfmxpriority" name="cfmxpriority" value="' . $dnsdetails["result"]["priority"] . '"><small>0-65355</small></div>';
                    break;
                case "SPF":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_content'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="" placeholder="v=spf..."></div>';
                case "TXT":
                    $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_content'] . ':</label><textarea class="form-control" id="cfdnsvalue" name="cfdnsvalue">' . $dnsdetails["result"]["content"] . '</textarea></div>';
                    break;
            }

            //            if ($dnsdetails["result"]["type"] == "A" || $dnsdetails["result"]["type"] == "AAAA" || $dnsdetails["result"]["type"] == "CNAME") {
            //                $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_value'] . ':</label><input type="text" class="form-control" id="cfdnsvalue" name="cfdnsvalue" value="' . $dnsdetails["result"]["content"] . '"></div>';
            //                $html .= '<div class="form-group"><label for="proxied">' . $language['cf_dns_proxied_status'] . ':</label>'
            //                        . '<select class="form-control" name="proxied">'
            //                        . '<option value="true" ' . $trueSelect . '>' . $language['cf_dns_proxied_on'] . '</option>'
            //                        . '<option value="false" ' . $falseSelect . '>' . $language['cf_dns_proxied_off'] . '</option>'
            //                        . '</select>'
            //                        . '</div>';
            //            }
            //            if ($dnsdetails["result"]["type"] == "TXT") {
            //                $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_value'] . ':</label><textarea class="form-control"  name="cfdnsvalue">' . $dnsdetails["result"]["content"] . '</textarea></div>';
            //            }
            //            if ($dnsdetails["result"]["type"] == "MX") {
            //                $html .= '<div class="form-group"><label for="cfdnsvalue">' . $language['cf_dns_server'] . ':</label><textarea class="form-control"  name="cfdnsvalue">' . $dnsdetails["result"]["content"] . '</textarea></div>';
            //                $html .= '<div class="form-group"><label for="cfmxpriority">' . $language['cf_dns_priority'] . ':</label><textarea class="form-control"  name="cfmxpriority">' . $dnsdetails["result"]["priority"] . '</textarea></div>';
            //            }
            $html .= '<div class="form-group"><label for="cfdnsttl">' . $language['cf_dns_ttl'] . ':</label>'
                . '<select name="cfdnsttl" id="cfdnsttl" class="form-control" id="cfdnsttl" ' . $ttldisabled . '>';
            foreach ($dnsttlvalues as $key => $value) {
                $html .= '<option value="' . $key . '">' . $value . '</option>';
            }
            $html .= '</select>';
            if ($ttldisabled != '') {
                $html .= ' ' . $language['cf_dns_ajax_ttl_manage'];
            }
            $html .= '</div>';
            $script = '<script>jQuery(document).ready(function(){'
                . 'jQuery("#proxied").change(function(){'
                . 'if(jQuery(this).val() == "false"){'
                . 'jQuery("#cfdnsttl").attr("disabled", false);'
                . '}else{'
                . 'jQuery("#cfdnsttl").attr("disabled", true);'
                . '}'
                . '});'
                . '});</script>';

            print $html . $script;
            break;
    }
}

exit();