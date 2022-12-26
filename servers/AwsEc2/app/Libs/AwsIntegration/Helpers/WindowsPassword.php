<?php

namespace ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\Helpers;

class WindowsPassword
{
    public function decode($password = '', $key = null)
    {
        $base64decoded = base64_decode($password);

        $parsedKey = openssl_get_privatekey($key);
        $decodedPassword = '';
        if(openssl_private_decrypt($base64decoded, $decodedPassword, $parsedKey))
        {
            return $decodedPassword;
        }

        return false;
    }
}
