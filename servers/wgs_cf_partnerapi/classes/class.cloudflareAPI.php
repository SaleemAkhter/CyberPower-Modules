<?php

## Cloudflare Host API

class CF_HOST_API
{

    //The URL of the API
    private static $URL = array(
        'USER' => 'https://www.cloudflare.com/api_json.html',
        'HOST' => 'https://api.cloudflare.com/host-gw.html'
    );
    //Service mode values.
    private static $MODE_SERVICE = array('A', 'AAAA', 'CNAME');
    //Prio values.
    private static $PRIO = array('MX', 'SRV');

    //Timeout for the API requests in seconds
    const TIMEOUT = 10;
    //Interval values for Stats
    const INTERVAL_365_DAYS = 10;
    const INTERVAL_30_DAYS = 20;
    const INTERVAL_7_DAYS = 30;
    const INTERVAL_DAY = 40;
    const INTERVAL_24_HOURS = 100;
    const INTERVAL_12_HOURS = 110;
    const INTERVAL_6_HOURS = 120;

    //Stores the api key
    private $token_key;
    private $host_key;
    //Stores the email login
    private $email;

    /**
     * Make a new instance of the API client
     */
    public function __construct()
    {
        $parameters = func_get_args();

        switch (func_num_args()) {
            case 1:
                //a host API
                $this->host_key = $parameters[0];
                break;
            case 2:
                //a user request
                $this->email = $parameters[0];
                $this->token_key = $parameters[1];
                break;
        }
    }

    public function user_create($email, $password, $username = '', $id = '')
    {
        $data = array(
            'act' => 'user_create',
            'cloudflare_email' => $email,
            'cloudflare_pass' => $password,
            'cloudflare_username' => $username,
            'unique_id' => $id
        );
        return $this->http_post($data, 'HOST');
    }

    public function reseller_sub_new($key, $zone, $planTag)
    {
        $data = array(
            'act' => 'reseller_sub_new',
            'user_key' => $key,
            'zone_name' => $zone,
            'plan_tag' => $planTag
        );
        return $this->http_post($data, 'HOST');
    }

    public function reseller_sub_cancel($key, $zone, $planTag, $sub_id)
    {
        $data = array(
            'act' => 'reseller_sub_cancel',
            'user_key' => $key,
            'zone_name' => $zone,
            'plan_tag' => $planTag,
            'sub_id' => $sub_id,
        );
        return $this->http_post($data, 'HOST');
    }

    public function zone_set($key, $zone, $resolve_to, $subdomains, $zoneType)
    {
        if (is_array($subdomains))
            $subdomains = implode(',', $subdomains);
        $data = array(
            'act' => $zoneType,
            'user_key' => $key,
            'zone_name' => $zone,
            'resolve_to' => $resolve_to,
            'subdomains' => $subdomains
        );
        if ($zoneType != 'zone_set') {
            unset($data['resolve_to']);
            unset($data['subdomains']);
        }
        return $this->http_post($data, 'HOST');
    }

    public function getCfNameservers($message)
    {
        $tokens = preg_split("/[\s,\s]/", $message);
        $nameservers = array();

        foreach ($tokens as $token) {
            if (preg_match("/ns.cloudflare.com/", $token))
                $nameservers[] = $token;
        }

        return $nameservers;
    }

    public function user_lookup($email, $isID = false)
    {
        $data = array(
            'act' => 'user_lookup'
        );
        if ($isID) {
            $data['unique_id'] = $email;
        } else {
            $data['cloudflare_email'] = $email;
        }
        return $this->http_post($data, 'HOST');
    }

    public function user_auth($email, $password, $id = '')
    {
        $data = array(
            'act' => 'user_auth',
            'cloudflare_email' => $email,
            'cloudflare_pass' => $password,
            'unique_id' => $id
        );
        return $this->http_post($data, 'HOST');
    }

    public function zone_lookup($zone, $user_key)
    {
        $data = array(
            'act' => 'zone_lookup',
            'user_key' => $user_key,
            'zone_name' => $zone
        );
        return $this->http_post($data, 'HOST');
    }

    public function reseller_plan_list()
    {
        $data = array(
            'act' => 'reseller_plan_list'
        );
        return $this->http_post($data, 'HOST');
    }

    public function zone_delete($zone, $user_key)
    {
        $data = array(
            'act' => 'zone_delete',
            'user_key' => $user_key,
            'zone_name' => $zone
        );
        return $this->http_post($data, 'HOST');
    }

    public function zone_list($zone_name = null, $sub_id = null)
    {
        $data = array(
            'act' => 'zone_list',
            'zone_status' => 'ALL',
            'sub_status' => 'ALL'
        );
        if ($zone_name)
            $data = array_merge($data, array('zone_name' => $zone_name));
        if ($sub_id)
            $data = array_merge($data, array('sub_id' => $sub_id));
        return $this->http_post($data, 'HOST');
    }

    ############
    # Client API

    #
    #
    public function zone_load_multi($email)
    {
        $data = array(
            'a' => 'zone_load_multi',
            'email' => $email
        );
        return $this->http_post($data);
    }

    #
    #
    ##############

    private function http_post($data, $type = 'USER')
    {
        switch ($type) {
            case 'USER':
                $data['u'] = $this->email;
                $data['tkn'] = $this->token_key;
                break;
            case 'HOST':
                $data['host_key'] = $this->host_key;
                break;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_URL, self::$URL[$type]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $http_result = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code != 200) {
            return array(
                'error' => $error
            );
        } else {
            return json_decode($http_result);
        }
    }
}

## Encodes Special Characters: Specially for Western European Languages

class cloudflare_Encoding
{

    const ICONV_TRANSLIT = "TRANSLIT";
    const ICONV_IGNORE = "IGNORE";
    const WITHOUT_ICONV = "";

    protected static $win1252ToUtf8 = array(
        128 => "\xe2\x82\xac",
        130 => "\xe2\x80\x9a",
        131 => "\xc6\x92",
        132 => "\xe2\x80\x9e",
        133 => "\xe2\x80\xa6",
        134 => "\xe2\x80\xa0",
        135 => "\xe2\x80\xa1",
        136 => "\xcb\x86",
        137 => "\xe2\x80\xb0",
        138 => "\xc5\xa0",
        139 => "\xe2\x80\xb9",
        140 => "\xc5\x92",
        142 => "\xc5\xbd",
        145 => "\xe2\x80\x98",
        146 => "\xe2\x80\x99",
        147 => "\xe2\x80\x9c",
        148 => "\xe2\x80\x9d",
        149 => "\xe2\x80\xa2",
        150 => "\xe2\x80\x93",
        151 => "\xe2\x80\x94",
        152 => "\xcb\x9c",
        153 => "\xe2\x84\xa2",
        154 => "\xc5\xa1",
        155 => "\xe2\x80\xba",
        156 => "\xc5\x93",
        158 => "\xc5\xbe",
        159 => "\xc5\xb8"
    );
    protected static $brokenUtf8ToUtf8 = array(
        "\xc2\x80" => "\xe2\x82\xac",
        "\xc2\x82" => "\xe2\x80\x9a",
        "\xc2\x83" => "\xc6\x92",
        "\xc2\x84" => "\xe2\x80\x9e",
        "\xc2\x85" => "\xe2\x80\xa6",
        "\xc2\x86" => "\xe2\x80\xa0",
        "\xc2\x87" => "\xe2\x80\xa1",
        "\xc2\x88" => "\xcb\x86",
        "\xc2\x89" => "\xe2\x80\xb0",
        "\xc2\x8a" => "\xc5\xa0",
        "\xc2\x8b" => "\xe2\x80\xb9",
        "\xc2\x8c" => "\xc5\x92",
        "\xc2\x8e" => "\xc5\xbd",
        "\xc2\x91" => "\xe2\x80\x98",
        "\xc2\x92" => "\xe2\x80\x99",
        "\xc2\x93" => "\xe2\x80\x9c",
        "\xc2\x94" => "\xe2\x80\x9d",
        "\xc2\x95" => "\xe2\x80\xa2",
        "\xc2\x96" => "\xe2\x80\x93",
        "\xc2\x97" => "\xe2\x80\x94",
        "\xc2\x98" => "\xcb\x9c",
        "\xc2\x99" => "\xe2\x84\xa2",
        "\xc2\x9a" => "\xc5\xa1",
        "\xc2\x9b" => "\xe2\x80\xba",
        "\xc2\x9c" => "\xc5\x93",
        "\xc2\x9e" => "\xc5\xbe",
        "\xc2\x9f" => "\xc5\xb8"
    );
    protected static $utf8ToWin1252 = array(
        "\xe2\x82\xac" => "\x80",
        "\xe2\x80\x9a" => "\x82",
        "\xc6\x92" => "\x83",
        "\xe2\x80\x9e" => "\x84",
        "\xe2\x80\xa6" => "\x85",
        "\xe2\x80\xa0" => "\x86",
        "\xe2\x80\xa1" => "\x87",
        "\xcb\x86" => "\x88",
        "\xe2\x80\xb0" => "\x89",
        "\xc5\xa0" => "\x8a",
        "\xe2\x80\xb9" => "\x8b",
        "\xc5\x92" => "\x8c",
        "\xc5\xbd" => "\x8e",
        "\xe2\x80\x98" => "\x91",
        "\xe2\x80\x99" => "\x92",
        "\xe2\x80\x9c" => "\x93",
        "\xe2\x80\x9d" => "\x94",
        "\xe2\x80\xa2" => "\x95",
        "\xe2\x80\x93" => "\x96",
        "\xe2\x80\x94" => "\x97",
        "\xcb\x9c" => "\x98",
        "\xe2\x84\xa2" => "\x99",
        "\xc5\xa1" => "\x9a",
        "\xe2\x80\xba" => "\x9b",
        "\xc5\x93" => "\x9c",
        "\xc5\xbe" => "\x9e",
        "\xc5\xb8" => "\x9f"
    );

    static function toUTF8($text)
    {

        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::toUTF8($v);
            }
            return $text;
        } elseif (is_string($text)) {

            if (function_exists('mb_strlen') && ((int) ini_get('mbstring.func_overload')) & 2) {
                $max = mb_strlen($text, '8bit');
            } else {
                $max = strlen($text);
            }

            $buf = "";
            for ($i = 0; $i < $max; $i++) {
                $c1 = $text{
                    $i};
                if ($c1 >= "\xc0") { //Should be converted to UTF8, if it's not UTF8 already
                    $c2 = $i + 1 >= $max ? "\x00" : $text{
                        $i + 1};
                    $c3 = $i + 2 >= $max ? "\x00" : $text{
                        $i + 2};
                    $c4 = $i + 3 >= $max ? "\x00" : $text{
                        $i + 3};
                    if ($c1 >= "\xc0" & $c1 <= "\xdf") { //looks like 2 bytes UTF8
                        if ($c2 >= "\x80" && $c2 <= "\xbf") { //yeah, almost sure it's UTF8 already
                            $buf .= $c1 . $c2;
                            $i++;
                        } else { //not valid UTF8.  Convert it.
                            $cc1 = (chr(ord($c1) / 64) | "\xc0");
                            $cc2 = ($c1 & "\x3f") | "\x80";
                            $buf .= $cc1 . $cc2;
                        }
                    } elseif ($c1 >= "\xe0" & $c1 <= "\xef") { //looks like 3 bytes UTF8
                        if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf") { //yeah, almost sure it's UTF8 already
                            $buf .= $c1 . $c2 . $c3;
                            $i = $i + 2;
                        } else { //not valid UTF8.  Convert it.
                            $cc1 = (chr(ord($c1) / 64) | "\xc0");
                            $cc2 = ($c1 & "\x3f") | "\x80";
                            $buf .= $cc1 . $cc2;
                        }
                    } elseif ($c1 >= "\xf0" & $c1 <= "\xf7") { //looks like 4 bytes UTF8
                        if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf") { //yeah, almost sure it's UTF8 already
                            $buf .= $c1 . $c2 . $c3 . $c4;
                            $i = $i + 3;
                        } else { //not valid UTF8.  Convert it.
                            $cc1 = (chr(ord($c1) / 64) | "\xc0");
                            $cc2 = ($c1 & "\x3f") | "\x80";
                            $buf .= $cc1 . $cc2;
                        }
                    } else { //doesn't look like UTF8, but should be converted
                        $cc1 = (chr(ord($c1) / 64) | "\xc0");
                        $cc2 = (($c1 & "\x3f") | "\x80");
                        $buf .= $cc1 . $cc2;
                    }
                } elseif (($c1 & "\xc0") == "\x80") { // needs conversion
                    if (isset(self::$win1252ToUtf8[ord($c1)])) { //found in Windows-1252 special cases
                        $buf .= self::$win1252ToUtf8[ord($c1)];
                    } else {
                        $cc1 = (chr(ord($c1) / 64) | "\xc0");
                        $cc2 = (($c1 & "\x3f") | "\x80");
                        $buf .= $cc1 . $cc2;
                    }
                } else { // it doesn't need conversion
                    $buf .= $c1;
                }
            }
            return $buf;
        } else {
            return $text;
        }
    }

    static function toWin1252($text, $option = self::WITHOUT_ICONV)
    {
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::toWin1252($v, $option);
            }
            return $text;
        } elseif (is_string($text)) {
            return static::utf8_decode($text, $option);
        } else {
            return $text;
        }
    }

    static function toISO8859($text)
    {
        return self::toWin1252($text);
    }

    static function toLatin1($text)
    {
        return self::toWin1252($text);
    }

    static function fixUTF8($text, $option = self::WITHOUT_ICONV)
    {
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::fixUTF8($v, $option);
            }
            return $text;
        }

        $last = "";
        while ($last <> $text) {
            $last = $text;
            $text = self::toUTF8(static::utf8_decode($text, $option));
        }
        $text = self::toUTF8(static::utf8_decode($text, $option));
        return $text;
    }

    static function UTF8FixWin1252Chars($text)
    {
        // If you received an UTF-8 string that was converted from Windows-1252 as it was ISO8859-1
        // (ignoring Windows-1252 chars from 80 to 9F) use this function to fix it.
        // See: http://en.wikipedia.org/wiki/Windows-1252

        return str_replace(array_keys(self::$brokenUtf8ToUtf8), array_values(self::$brokenUtf8ToUtf8), $text);
    }

    static function removeBOM($str = "")
    {
        if (substr($str, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $str = substr($str, 3);
        }
        return $str;
    }

    public static function normalizeEncoding($encodingLabel)
    {
        $encoding = strtoupper($encodingLabel);
        $encoding = preg_replace('/[^a-zA-Z0-9\s]/', '', $encoding);
        $equivalences = array(
            'ISO88591' => 'ISO-8859-1',
            'ISO8859' => 'ISO-8859-1',
            'ISO' => 'ISO-8859-1',
            'LATIN1' => 'ISO-8859-1',
            'LATIN' => 'ISO-8859-1',
            'UTF8' => 'UTF-8',
            'UTF' => 'UTF-8',
            'WIN1252' => 'ISO-8859-1',
            'WINDOWS1252' => 'ISO-8859-1'
        );

        if ($equivalences[$encoding] == '') {
            return 'UTF-8';
        }

        return $equivalences[$encoding];
    }

    public static function encode($encodingLabel, $text)
    {
        $encodingLabel = self::normalizeEncoding($encodingLabel);
        if ($encodingLabel == 'UTF-8')
            return Encoding::toUTF8($text);
        if ($encodingLabel == 'ISO-8859-1')
            return Encoding::toLatin1($text);
    }

    protected static function utf8_decode($text, $option)
    {
        if ($option == self::WITHOUT_ICONV || !function_exists('iconv')) {
            $o = utf8_decode(
                str_replace(array_keys(self::$utf8ToWin1252), array_values(self::$utf8ToWin1252), self::toUTF8($text))
            );
        } else {
            $o = iconv("UTF-8", "Windows-1252" . ($option == self::ICONV_TRANSLIT ? '//TRANSLIT' : ($option == self::ICONV_IGNORE ? '//IGNORE' : '')), $text);
        }
        return $o;
    }
}
