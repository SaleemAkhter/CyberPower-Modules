<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers;

class Cidr
{
    public static function getBiggestBlock($ipinput)
    {
        return self::maskAsCidr(long2ip(-(ip2long($ipinput) & -(ip2long($ipinput)))));
    }

    public static function maskAsCidr($netmask)
    {
        return self::isNetmaskValid($netmask) ? self::countBits(ip2long($netmask)) : 35;
    }

    public static function isNetmaskValid($netmask)
    {
        $netmask = ip2long($netmask);
        if ($netmask === false) return false;
        $neg = ((~(int)$netmask) & 0xFFFFFFFF);
        return (($neg + 1) & $neg) === 0;
    }

    public static function countBits($val)
    {
        $val = $val & 0xFFFFFFFF;
        $val = ($val & 0x55555555) + (($val >> 1) & 0x55555555);
        $val = ($val & 0x33333333) + (($val >> 2) & 0x33333333);
        $val = ($val & 0x0F0F0F0F) + (($val >> 4) & 0x0F0F0F0F);
        $val = ($val & 0x00FF00FF) + (($val >> 8) & 0x00FF00FF);
        $val = ($val & 0x0000FFFF) + (($val >> 16) & 0x0000FFFF);
        $val = $val & 0x0000003F;
        return $val;
    }

    public static function rangeAsCidrList($startIP, $endIP = null)
    {
        $start = ip2long($startIP);
        $end = (empty($endIP)) ? $start : ip2long($endIP);
        while ($end >= $start) {
            $maxBlock = self::getBiggestBlock(long2ip($start));
            $maxDifference = 32 - intval(log($end - $start + 1) / log(2));
            $size = ($maxBlock > $maxDifference) ? $maxBlock : $maxDifference;
            $cidrList[] = long2ip($start) . "/$size";
            $start += pow(2, (32 - $size));
        }
        return $cidrList;
    }

    public static function cidrAsRange($cidr)
    {
        $ipRange = array();
        $cidr = explode('/', $cidr);
        $ipRange[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $ipRange[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
        return $ipRange;
    }
}