<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

class IP
{

    public static function getClientAddress()
    {
        $ordered_choices = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        $invalid_ips     = ['127.0.0.1', '::1'];
        foreach ($ordered_choices as $var)
        {
            if (isset($_SERVER[$var]))
            {
                $ip = $_SERVER[$var];
                if ($ip && !in_array($ip, $invalid_ips))
                {
                    $ips = explode(',', $ip);
                    return reset($ips);
                }
            }
        }
        return null;
    }

    public static function getServerAddress()
    {
        return $_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDRESS'];
    }

    public static function getDirectAdminIP($params)
    {
        $ip           = null;
        $directAdmin  = new DirectAdmin($params);
        $preferredIp  = $params['model']['dedicatedip'];
        $availableIps = [];

        // because if someone place reseller credentials in server config getFreeIp method wont work
        try
        {
            $result = $directAdmin->ip->getFreeIp();

            foreach ($result->getIps() as $each)
            {
                if (($params['serverusername'] === $each->reseller) && $each->status == 'free')
                {

                    if (empty($preferredIp))
                    {
                        $ip = $each->ip;
                        break;
                    }

                    if ($each->ip == $preferredIp)
                    {
                        $ip = $each->ip;
                        break;
                    }

                    $availableIps[] = $each->ip;
                }
            }
        }
        catch (ApiException $exc)
        {
            $result = $directAdmin->ip->getIpList();
            foreach ($result->response as $each)
            {
                $data   = [
                    'address' => $each->getAddress()
                ];
                $ipInfo = $directAdmin->ip->getIpInformation(new Command\Ip($data))->first();
                if ($ipInfo->getStatus() == 'free' && empty($preferredIp))
                {
                    $ip = $each->getAddress();
                    break;
                }

                if ($preferredIp == $each->getAddress())
                {
                    $ip = $each->getAddress();
                    break;
                }

                $availableIps[] = $each->getAddress();
            }
        }

        if ($preferredIp && $preferredIp != $ip)
        {
            if($availableIps)
            {
                throw new \Exception(sprintf("Cannot find %s. Available IPs:", $preferredIp, implode(',', $availableIps)));
            }
            else
            {
                throw new \Exception(sprintf("Cannot find %s. No available IPs", $preferredIp));
            }
        }

        return $ip;
    }
}
