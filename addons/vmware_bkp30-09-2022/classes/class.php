<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class VMWAREADDON {
    public function __construct(){}
    
    public function GetAdminId() {
        $result = Capsule::table('tbladmins')->select('id')->take(1)->get();
        return $result[0]->id;
    }

    public function vmwarePwEncryptDcrypt($passWord, $encrypt = null) {
        if ($encrypt)
            $command = "encryptpassword";
        else
            $command = "decryptpassword";

        $adminQuery = Capsule::table('tbladmins')->first();

        $adminuser = $adminQuery->id;
        $values["password2"] = $passWord;

        $results = localAPI($command, $values, $adminuser);
        return $results;
    }
/*
     * Encrypt Decrypt Data
     */

    public function vmwarePwencryption($string) {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $encrypted = base64_encode($iv . $hmac . $ciphertext_raw);
        return $encrypted;
    }

    public function vmwarePwdecryption($encrypted) {
        $data = base64_decode($encrypted);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($data, 0, $ivlen);
        $hmac = substr($data, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($data, $ivlen + $sha2len);
        $decrypted = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {//PHP 5.6+ timing attack safe comparison
            return $decrypted;
        }
    }

    public function vmwareGetWHMCSProductList() {
        $productResultArr = array();
        foreach (Capsule::table('tblproducts')->where('servertype', 'vmware')->get() as $productResult) {
            $productResult = (array) $productResult;
            $productResultArr[] = $productResult;
        }
        return $productResultArr;
    }

    public function wgsvmwareGetConfigurableOptionId($gid, $optionName = null) {
        $getOptionId = Capsule::table('tblproductconfigoptions')->where('gid', $gid)->where('optionname', 'like', '%' . $optionName . '%')->first();
        return $getOptionId->id;
    }

    public function wgsvmwareCheckConfigurableSubOption($configId, $optionName = null) {
        $count = Capsule::table('tblproductconfigoptionssub')->where('configid', $configId)->where('optionname', $optionName)->count();
        return $count;
    }

    public function wgsvmwareAddUpdateConfigurableSubOption($configId, $optionName = null, $existOptionName = null, $update = null) {

        if (!empty($optionName)) {
            if (empty($update))
                $count = $this->wgsvmwareCheckConfigurableSubOption($configId, $optionName);
            elseif ($update)
                $count = $this->wgsvmwareCheckConfigurableSubOption($configId, $existOptionName);

            $value = [
                'configid' => $configId,
                'optionname' => $optionName,
                'sortorder' => '',
                'hidden' => ''
            ];

            if ($count == 0) {
                $tblpricing_rel_id = Capsule::table('tblproductconfigoptionssub')->insertGetId($value);
                $this->wgsvmwareAddConfigurablePrice($tblpricing_rel_id);
            } elseif ($update)
                Capsule::table('tblproductconfigoptionssub')->where('configid', $configId)->where('optionname', $existOptionName)->update($value);
        }
    }

    public function wgsvmwareAddConfigurablePrice($tblpricing_rel_id) {
        $result = Capsule::table('tblcurrencies')->get();
        foreach ($result as $data) {
            $curr_id = $data->id;
            $curr_code = $data->code;
            $currenciesarray[$curr_id] = $curr_code;
        }

        foreach ($currenciesarray as $curr_id => $currency) {
            Capsule::table('tblpricing')->insertGetId(
                    [
                        'type' => 'configoptions',
                        'currency' => $curr_id,
                        'relid' => $tblpricing_rel_id,
                        'msetupfee' => '',
                        'qsetupfee' => '',
                        'ssetupfee' => '',
                        'asetupfee' => '',
                        'bsetupfee' => '',
                        'tsetupfee' => '',
                        'monthly' => '',
                        'quarterly' => '',
                        'semiannually' => '',
                        'annually' => '',
                        'biennially' => '',
                        'triennially' => '',
                    ]
            );
        }
    }

    public function wgsvmwareDeleteConfigurableSubOption($configId, $optionName) {
        Capsule::table('tblproductconfigoptionssub')->where('configid', $configId)->where('optionname', $optionName)->delete();
    }

}
