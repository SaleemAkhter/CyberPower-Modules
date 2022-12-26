<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class VMWAREOVHCLASS {

    var $timeDrift = 0;
    var $secretKey = null;
    var $consumerKey = null;
    var $appliocationKey = null;

    public function __construct() {
        
    }

    public function CreateDbTables() {
        try {
            if (!Capsule::Schema()->hasTable('mod_ovh_manage_apps')) {
                Capsule::schema()->create(
                        'mod_ovh_manage_apps', function ($table) {
                    $table->increments('id');
                    $table->string('secret_key')->nullable();
                    $table->string('consumer_key')->nullable();
                    $table->string('location')->nullable();
                    $table->string('application_key')->nullable();
                    $table->string('service_location')->nullable();
                    $table->string('api_service_provider')->nullable();
                    $table->string('account_number')->nullable();
                    $table->string('status')->nullable();
                }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_ovh_manage_apps: {$e->getMessage()}");
        }

        try {
            if (!Capsule::Schema()->hasTable('mod_ovh_imap')) {
                Capsule::schema()->create(
                        'mod_ovh_imap', function ($table) {
                    $table->increments('id');
                    $table->string('soyouimaphost')->nullable();
                    $table->string('soyouimapuser')->nullable();
                    $table->string('soyouimappass')->nullable();
                    $table->string('soyouimapport')->nullable();
                    $table->string('soyouimapssl', 50)->nullable();
                    $table->string('created_at', 50)->nullable();
                    $table->string('language', 50)->nullable();
                    $table->string('account_user', 50)->nullable();
                    $table->string('status', '50');
                }
                );
            }
        } catch (\Exception $e) {
            logActivity("Unable to create mod_ovh_imap: {$e->getMessage()}");
        }
    }

    public function appCreateEmailTemplate($tempData) {
        if (Capsule::table('tblemailtemplates')->select('id')->where('name', $tempData['name'])->count() == 0) {
            try {
                Capsule::table('tblemailtemplates')->insert(
                        [
                            "type" => $tempData['type'],
                            "name" => $tempData['name'],
                            "subject" => $tempData['subject'],
                            "message" => $tempData['message'],
                            "custom" => 1,
                            "plaintext" => 0
                        ]
                );
            } catch (Exception $ex) {
                logActivity("Could't insert into table tblemailtemplates: {$ex->getMessage()}");
            }
        }
    }

    public function app_imapconnection($data) {
        if ($data->soyouimapport != "") {
            $port = $data->soyouimapport;
        } else {
            $port = 143;
        }
        if ($data->soyouimapssl == 'tls' || $data->soyouimapssl == 'default') {
            $layer = 'tls';
        } else {
            $layer = 'ssl';
        }
        $certvalidate = 'novalidate-cert';
        $hostname = '{' . $data->soyouimaphost . ':' . $port . '/imap/' . $layer . '/' . $certvalidate . '/norsh}INBOX';
        $username = trim($data->soyouimapuser);
        $password = trim($data->soyouimappass);
        $password = decrypt($password);
        $inbox = imap_open($hostname, $username, $password);
        if (!empty($inbox)) {
            return 'Active';
        } else {
            return imap_last_error();
        }
    }

    public function getVmIps($vmname) {
        $data = Capsule::table('mod_vmware_ip_list')->where('forvm', $vmname)->get();
        $result = [];
        foreach ($data as $value) {
            $result[] = (array) $value;
        }
        return $result;
    }

    public function getDatacenterLocation($vmname) {
        $data = Capsule::table('mod_vmware_ip_list')->select('location')->where('forvm', $vmname)->groupBy('location')->first();
        return $data->location;
    }

    public function getAPP_Detail($serviceProvide) {
        $data = Capsule::table('mod_ovh_manage_apps')->where('api_service_provider', $serviceProvide)->first();
        $result = (array) $data;
        $result['secret_key'] = decrypt($data->secret_key);
        $result['consumer_key'] = decrypt($data->consumer_key);
        $result['application_key'] = decrypt($data->application_key);
        return $result;
    }

    public function appPost($url, $data = null) {
        return $this->appDoCall('POST', $url, $data);
    }

    public function appPut($url, $data = null) {
        return $this->appDoCall('PUT', $url, $data);
    }

    public function appGet($url) {
        return $this->appDoCall('GET', $url);
    }

    public function appDelete($url, $data = null) {
        return $this->appDoCall('DELETE', $url, $data);
    }

    public function appGetAllIps() {
        return $this->appGet('/ip');
    }

    public function appGetIpDetail($ip = null) {
        return $this->appGet('/ip/' . urlencode($ip));
    }

    public function appGetFirewall($ipBlock, $ip) {
        return $this->appGet('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip));
    }

    public function appGetFirewallRule($ipBlock, $ip) {
        return $this->appGet('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip) . '/rule');
    }

    public function appGetFirewallRuleSequence($ipBlock, $ip, $sequence) {
        return $this->appGet('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip) . '/rule/' . $sequence);
    }

    public function appCreateFirewall($ipBlock, $ip) {
        $data = '{
        "ipOnFirewall": "' . urlencode($ip) . '"
        }';
        $response = $this->appPost('/ip/' . urlencode($ipBlock) . '/firewall', $data);
        return $response;
    }

    public function appEnableFirewall($ipBlock, $ip) {
        $data = '{
        "enabled": "true"
        }';
        $response = $this->appPut('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip), $data);
        return $response;
    }

    public function appDisableFirewall($ipBlock, $ip) {
        $data = '{
        "enabled": "false"
        }';
        $response = $this->appPut('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip), $data);
        return $response;
    }

    public function appRemoveFirewall($ipBlock, $ip) {
        $response = $this->appDelete('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip));
        return $response;
    }

    public function appCreateFirewallRule($ipBlock, $ip, $data) {
        $response = $this->appPost('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip) . '/rule', $data);
        return $response;
    }

    public function appRemoveFirewallRule($ipBlock, $ip, $sequence) {
        $response = $this->appDelete('/ip/' . urlencode($ipBlock) . '/firewall/' . urlencode($ip) . '/rule/' . $sequence);
        return $response;
    }

    public function appGetReverseIp($ip, $reverseIP) {
        return $this->appGet('/ip/' . urlencode($ip) . '/reverse/' . $reverseIP);
    }

    public function appSetReverseIp($ipBlock, $ip, $reverse) {
        $data = '{
        "ipReverse": "' . urlencode($ip) . '",
        "reverse": "' . $reverse . '"
        }';
        $response = $this->appPost('/ip/' . urlencode($ipBlock) . '/reverse', $data);
        return $response;
    }

    public function appRemoveReverseIp($ipBlock, $ip) {
        $response = $this->appDelete('/ip/' . urlencode($ipBlock) . '/reverse/' . urlencode($ip));
        return $response;
    }

    function appIpInRange($ip, $range) {
        if (strpos($range, '/') !== false) {
            // $range is in IP/NETMASK format
            list($range, $netmask) = explode('/', $range, 2);
            if (strpos($netmask, '.') !== false) {
                $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);
                return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
            } else {
                // $netmask is a CIDR size block
                // fix the range argument
                $x = explode('.', $range);
                while (count($x) < 4)
                    $x[] = '0';
                list($a, $b, $c, $d) = $x;
                $range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);

                # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
                #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));
                # Strategy 2 - Use math to create it
                $wildcard_dec = pow(2, (32 - $netmask)) - 1;
                $netmask_dec = ~ $wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        } else {
            // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
            if (strpos($range, '*') !== false) { // a.b.*.* format
                // Just convert to A-B format by setting * to 0 for A and 255 for B
                $lower = str_replace('*', '0', $range);
                $upper = str_replace('*', '255', $range);
                $range = "$lower-$upper";
            }

            if (strpos($range, '-') !== false) { // A-B format
                list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = (float) sprintf("%u", ip2long($lower));
                $upper_dec = (float) sprintf("%u", ip2long($upper));
                $ip_dec = (float) sprintf("%u", ip2long($ip));
                return ( ($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec) );
            }
            return false;
        }
    }

    public function setAPP_Detail($appDetail) {
        $this->secretKey = $appDetail['secret_key'];
        $this->consumerKey = $appDetail['consumer_key'];
        $this->appliocationKey = $appDetail['application_key'];
        $url = substr($appDetail['location'], 0, -10);
        $this->ROOT = $url . '1.0';
        $this->appCalculateTimeDelta(); // calculating time
        $this->timeDrift = time() + $this->timeDrift;
    }

    public function appDoCall($method, $url, $dada = null) {
        $url = $this->ROOT . $url;
        $time = $this->timeDrift;
        $toSign = $this->secretKey . '+' . $this->consumerKey . '+' . $method . '+' . $url . '+' . $dada . '+' . $time;
        $signature = '$1$' . sha1($toSign);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-Ovh-Application:' . $this->appliocationKey, 'X-Ovh-Consumer:' . $this->consumerKey, 'X-Ovh-Timestamp:' . $time, 'X-Ovh-Signature:' . $signature));

        if ($dada) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dada);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        if ($result === false) {
            $result = curl_error($curl);
        } else {
            $result;
        }
//        die('testing123');
        return $result;
    }

    function appCalculateTimeDelta() {
        $request_timestamp_url = $this->ROOT . '/auth/time';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $request_timestamp_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);

        curl_close($ch);
        $response = $output;
        $serverTimestamp = (int) $response;
        $this->timeDrift = $serverTimestamp - (int) \time();
        return $this->timeDrift;
    }

}
