<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\Cidr;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Validators\BaseValidator;


class IpValidator extends BaseValidator
{

    /**
     * return true if data is valid, false if not,
     * add error messages to $errorsList
     *
     * @param $data           mixed
     * @param $additionalData mixed
     * @return boolean
     */
    protected function validate($data, $additionalData = null )
    {
        if($data === '')
        {
            return true;
        }

        if(strpos($data, "::") === 0)
            return !(substr($data,3) < 0 || substr($data,3) > 128);

        $ips = explode(',', $data);

        foreach ($ips as $ip)
        {
            if($this->validateIp(trim($ip)) && $this->validateCidr(trim($ip)))
                continue;
            return false;
        }

        return true;
    }

    private function validateIp($ip)
    {
        if(!preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}(\/([0-9]|[1-2][0-9]|3[0-2]))?$/', $ip))
        {
            $this->addValidationError('wrongIpFormat');
            return false;
        }
        return true;
    }

    private function validateCidr($cidr)
    {
        $parts = explode('/', $cidr);
        if(count($parts) != 2) {
            $parts[1] = 32;
        }

        $ip = $parts[0];
        $netmask = intval($parts[1]);

        if(!$this->isBlockValid($parts[0], $parts[1])){
            $this->addValidationError('notValidBlock');
            return false;
        }

        if($netmask < 0) {
            $this->addValidationError('wrongCidr');
            return false;

        }

        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if($netmask <= 32)
                return true;
            else {
                $this->addValidationError('wrongCidr');
                return false;
            }
        }

        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $netmask <= 128;
        }
        return false;
    }

    private function isBlockValid($ip, $block){
        if(Cidr::getBiggestBlock($ip) <= $block)
            return true;
        return false;
    }


}