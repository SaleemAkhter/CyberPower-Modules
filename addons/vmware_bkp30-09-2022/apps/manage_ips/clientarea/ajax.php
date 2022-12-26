<?php

use Illuminate\Database\Capsule\Manager as Capsule;

switch ($appajaxaction) {
    case 'getipreverse':
        $ip = $ipBlockArr[0];
        $assignedIp = $whmcs->get_req_var('ip');
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipReverse = json_decode($vmWareovhapp->appGetReverseIp($assignedIpBlock, $assignedIp), true);
                if (!empty($ipReverse['reverse']))
                    echo $ipReverse['reverse'];
                else
                    echo '-';
            }
        }
        exit();
        break;
    case 'setreverse':
        $reverse = $whmcs->get_req_var('reverse');
        $assignedIp = $whmcs->get_req_var('ip');
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $setIpReverse = json_decode($vmWareovhapp->appSetReverseIp($assignedIpBlock, $assignedIp, $reverse), true);
                if (!empty($setIpReverse['reverse']) && !empty($setIpReverse['ipReverse'])) {
                    echo 'success';
                } else {
                    echo $setIpReverse['message'];
                }
            } else {
                echo $LANG['app_wrong_ip'];
            }
        }
        exit();
        break;
    case 'removereverse':
        $assignedIp = $whmcs->get_req_var('ip');
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $removeIpReverse = json_decode($vmWareovhapp->appRemoveReverseIp($assignedIpBlock, $assignedIp), true);
                if (empty($removeIpReverse['message'])) {
                    echo 'success';
                } else {
                    echo $removeIpReverse['message'];
                }
            } else {
                echo $LANG['app_wrong_ip'];
            }
        }
        exit();
        break;
    case 'getfirewall':
        $assignedIp = $whmcs->get_req_var('ip');
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipFirewall = json_decode($vmWareovhapp->appGetFirewall($assignedIpBlock, $assignedIp), true);
                $status = '';
                $stsArr = [];
                if (!empty($ipFirewall['ipOnFirewall'])) {
                    if ($ipFirewall['state'] == 'ok' && $ipFirewall['enabled'] === true) {
                        $status = '<font color="green" class="firewallenabled">' . $LANG['app_enabled'] . '</font>';
                        $stsArr = ['status' => 1, 'msg' => $status, 'showbtn' => 'yes'];
                    } elseif ($ipFirewall['state'] == 'ok' && $ipFirewall['enabled'] === false) {
                        $status = '<font color="orange" class="firewalldisabled">' . $LANG['app_disabled'] . '</font>';
                        $stsArr = ['status' => 0, 'msg' => $status, 'showbtn' => 'yes'];
                    } elseif ($ipFirewall['state'] == 'enableFirewallPending') {
                        $status = '<font color="green" class="firewallenabled">' . $LANG['app_enabled'] . '</font>';
                        $stsArr = ['status' => 1, 'msg' => $status, 'showbtn' => 'yes'];
                    } elseif ($ipFirewall['state'] == 'disableFirewallPending') {
                        $status = '<font color="orange" class="firewalldisabled">' . $LANG['app_disabled'] . '</font>';
                        $stsArr = ['status' => 0, 'msg' => $status, 'showbtn' => 'yes'];
                    }
                } else {
                    $stsArr = ['status' => 0, 'msg' => 'createfirewall'];
                }
            } else {
                $stsArr = ['status' => 0, 'msg' => $LANG['app_wrong_ip']];
            }
            print json_encode($stsArr);
        }
        exit();
        break;
    case 'createfirewall':
        $assignedIp = $whmcs->get_req_var('ip');
        $stsArr = [];
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipFirewall = json_decode($vmWareovhapp->appCreateFirewall($assignedIpBlock, $assignedIp), true);
                if (!empty($ipFirewall['message'])) {
                    $stsArr = ['status' => 'error', 'msg' => $ipFirewall['message']];
                } else {
                    $stsArr = ['status' => 'success'];
                }
            } else {
                $stsArr = ['status' => 'error', 'msg' => $LANG['app_wrong_ip']];
            }
        } else
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_ip_missing']];
        print json_encode($stsArr);
        break;
    case 'enablefirewall':
        $assignedIp = $whmcs->get_req_var('ip');
        $stsArr = [];
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipFirewall = json_decode($vmWareovhapp->appEnableFirewall($assignedIpBlock, $assignedIp), true);
                if (!empty($ipFirewall['message'])) {
                    $stsArr = ['status' => 'error', 'msg' => $ipFirewall['message']];
                } else {
                    $stsArr = ['status' => 'success'];
                }
            } else {
                $stsArr = ['status' => 'error', 'msg' => $LANG['app_wrong_ip']];
            }
        } else
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_ip_missing']];
        print json_encode($stsArr);
        break;
    case 'disablefirewall':
        $assignedIp = $whmcs->get_req_var('ip');
        $stsArr = [];
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipFirewall = json_decode($vmWareovhapp->appDisableFirewall($assignedIpBlock, $assignedIp), true);
                if (!empty($ipFirewall['message'])) {
                    $stsArr = ['status' => 'error', 'msg' => $ipFirewall['message']];
                } else {
                    $stsArr = ['status' => 'success'];
                }
            } else {
                $stsArr = ['status' => 'error', 'msg' => $LANG['app_wrong_ip']];
            }
        } else
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_ip_missing']];
        print json_encode($stsArr);
        break;
    case 'removefirewall':
        $assignedIp = $whmcs->get_req_var('ip');
        $stsArr = [];
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $ipFirewall = json_decode($vmWareovhapp->appRemoveFirewall($assignedIpBlock, $assignedIp), true);
                if (!empty($ipFirewall['message'])) {
                    $stsArr = ['status' => 'error', 'msg' => $ipFirewall['message']];
                } elseif (isset($ipFirewall['deleted'])) {
                    $stsArr = ['status' => 'success'];
                }
            } else {
                $stsArr = ['status' => 'error', 'msg' => $LANG['app_wrong_ip']];
            }
        } else
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_ip_missing']];
        print json_encode($stsArr);
        break;
    case 'configurefirewallrule':
        $assignedIp = $whmcs->get_req_var('ip');

        if (!in_array($assignedIp, $ipArr)) {
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_wrong_ip']];
            print json_encode($stsArr);
            exit();
        }
        $priority = $whmcs->get_req_var('priority');
        $action = $whmcs->get_req_var('firewallaction');
        $protocol = $whmcs->get_req_var('protocol');
        $sourcreIp = $whmcs->get_req_var('sourceip');
        if ($sourcreIp == 'any')
            $sourcreIp = 'All';
        $sourcrePort = $whmcs->get_req_var('sourceport');
        $destinationPort = $whmcs->get_req_var('destinationport');
        $fragments = $whmcs->get_req_var('fragments');
        if ($fragments == 'on')
            $fragments = true;
        else
            $fragments = false;

        $flags = $whmcs->get_req_var('tcpoption');
        if (empty($action)) {
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_action_req']];
            print json_encode($stsArr);
            exit();
        } elseif (empty($protocol)) {
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_protocol_req']];
            print json_encode($stsArr);
            exit();
        }

        if ($protocol == 'tcp') {
            if (!empty($sourcrePort) && !empty($destinationPort))
                $dataArr = array('action' => $action, 'destinationPort' => $destinationPort, 'protocol' => $protocol, 'sequence' => $priority, 'sourcePort' => $sourcrePort, 'tcpOption' => array('fragments' => $fragments, 'flags' => $flags));
            elseif (!empty($sourcrePort))
                $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority, 'sourcePort' => $sourcrePort, 'tcpOption' => array('fragments' => $fragments, 'flags' => $flags));
            elseif (!empty($destinationPort))
                $dataArr = array('action' => $action, 'destinationPort' => $destinationPort, 'protocol' => $protocol, 'sequence' => $priority, 'tcpOption' => array('fragments' => $fragments, 'flags' => $flags));
            else
                $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority, 'tcpOption' => array('fragments' => $fragments, 'flags' => $flags));
        } elseif ($protocol == 'udp') {
            if (!empty($destinationPort))
                $dataArr = array('action' => $action, 'destinationPort' => $destinationPort, 'protocol' => $protocol, 'sequence' => $priority);
            elseif (!empty($sourcrePort))
                $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority, 'sourcePort' => $sourcrePort);
            elseif (!empty($sourcrePort) && !empty($destinationPort))
                $dataArr = array('action' => $action, 'destinationPort' => $destinationPort, 'protocol' => $protocol, 'sequence' => $priority, 'sourcePort' => $sourcrePort);
            else
                $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority);
        } else {
//            $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority, 'source' => $sourcreIp);
            $dataArr = array('action' => $action, 'protocol' => $protocol, 'sequence' => $priority);
        }
        if (!empty($sourcreIp))
            $dataArr['source'] = $sourcreIp;
        if (!empty($assignedIp)) {
            $firewallRule = json_decode($vmWareovhapp->appCreateFirewallRule($assignedIpBlock, $assignedIp, json_encode($dataArr)), true);
            if (!empty($firewallRule['message'])) {
                $stsArr = ['status' => 'error', 'msg' => $firewallRule['message']];
            } elseif (isset($firewallRule['state'])) {
                $stsArr = ['status' => 'success'];
            }
        } else
            $stsArr = ['status' => 'error', 'msg' => $LANG['app_ip_missing']];
        print json_encode($stsArr);
        exit();
        break;
    case 'getfirewallrule':
        $assignedIp = $whmcs->get_req_var('ip');
        if (!empty($assignedIp)) {
            $html = '<div class="table-responsive"> 
                    <table class="table">
                    <thead>
                      <tr>
                        <th title="' . $LANG['app_high_priority'] . '" width="8%">' . $LANG['app_priority'] . '</th>
                        <th width="12%">' . $LANG['app_action'] . '</th>
                        <th width="8%">' . $LANG['app_protocol'] . '</th>
                        <th width="12%">' . $LANG['app_sourceip'] . '</th>
                        <th width="14%">' . $LANG['app_source_port'] . '</th>
                        <th width="14%">' . $LANG['app_destination_port'] . '</th>
                        <th width="14%">' . $LANG['app_options'] . '</th>
                        <th width="10%">' . $LANG['app_state'] . '</th>
                        <th width="8%">&nbsp;</th>
                      </tr>
                    </thead><tbody>';
            if (in_array($assignedIp, $ipArr)) {
                $firewallRules = json_decode($vmWareovhapp->appGetFirewallRule($assignedIpBlock, $assignedIp), true);
                if (empty($firewallRules['message'])) {
                    if (!empty($firewallRules)) {
                        foreach ($firewallRules as $sequence) {
                            $firewallRule = json_decode($vmWareovhapp->appGetFirewallRuleSequence($assignedIpBlock, $assignedIp, $sequence), true);
                            if ($firewallRule['action'] == 'permit')
                                $action = 'To allow';
                            else
                                $action = 'Deny';
                            if ($firewallRule['source'] == 'any')
                                $source = 'All';
                            else
                                $source = $firewallRule['source'];
                            $fragments = '';
                            if (!empty($firewallRule['fragments']))
                                $fragments = $LANG['app_fragments'];
                            $state = '';
                            if ($firewallRule['state'] == 'ok')
                                $state = '<font color="green">' . $LANG['app_activated'] . '</font>';
                            elseif ($firewallRule['state'] == 'creationPending')
                                $state = '<font color="orange">' . $LANG['app_in_creation'] . '</font>';
                            elseif ($firewallRule['state'] == 'removalPending')
                                $state = $LANG['app_in_deletion'];
                            $html .= '
                                <tr>
                                  <td>' . $firewallRule['sequence'] . '</td>
                                  <td>' . $action . '</td>
                                  <td>' . strtoupper($firewallRule['protocol']) . '</td>
                                  <td>' . $source . '</td>
                                  <td>' . $firewallRule['sourcePort'] . '</td>
                                  <td>' . $firewallRule['destinationPort'] . '</td>
                                  <td>' . $fragments . '<br />' . ucfirst($firewallRule['tcpOption']) . '</td>
                                  <td>' . $state . '</td>                                      
                                  <td><i class="fa fa-trash" aria-hidden="true" title="Delete the rule" onclick="removeFirewallRule(this, \'' . $firewallRule['sequence'] . '\', \'' . $assignedIp . '\', \'' . $LANG['app_firewall_rule_removed_success'] . '\')"></i></td>
                                </tr>
                           ';
                        }
                    }else {
                        $html .= '
                                <tr>
                                  <td colspan="100%" align="center">' . $LANG['app_firewall_not_found'] . '</td>
                                </tr>
                           ';
                    }
                } else {
                    $html .= '
                                <tr>
                                  <td colspan="100%" align="center">' . $firewallRule['message'] . '</td>
                                </tr>
                           ';
                }
            } else {
                $html .= '
                                <tr>
                                  <td colspan="100%" align="center">' . $LANG['app_wrong_ip'] . '</td>
                                </tr>
                           ';
            }

            $html .= '</tbody></table></div>';
        }
        echo $html;
        exit();
        break;
    case 'removefirewallrule':
        $assignedIp = $whmcs->get_req_var('ip');
        $sequence = $whmcs->get_req_var('sequence');
        if (!empty($assignedIp)) {
            if (in_array($assignedIp, $ipArr)) {
                $deleteFirewallRule = json_decode($vmWareovhapp->appRemoveFirewallRule($assignedIpBlock, $assignedIp, $sequence), true);
                if (empty($deleteFirewallRule['message'])) {
                    print json_encode(['status' => 'success']);
                } else
                    print json_encode(['status' => 'error', 'msg' => $deleteFirewallRule['message']]);
            } else
                print json_encode(['status' => 'error', 'msg' => $LANG['app_wrong_ip']]);
        }
        exit();
        break;
}    