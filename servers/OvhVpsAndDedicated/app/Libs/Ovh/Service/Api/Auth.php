<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Ip\Ip as IpItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 */
class Auth extends AbstractApi
{
    const METHODS = ['GET', 'PUT', 'DELETE', 'POST'];

    public function credential($redirection = '', $accessRules = [])
    {
        $accessRules = empty($accessRules) ? $this->getDefaultAccessRules() : $accessRules;

        return $this->requestCredentials($accessRules, $redirection);
    }

    private function getDefaultAccessRules()
    {
        $accessRules = [];
        foreach ($this->getDefaultPaths() as $path)
        {
            foreach (self::METHODS as $method)
            {
                $accessRules[] = [
                    'method' => $method,
                    'path' => $path,
                ];
            }
        }
        return $accessRules;
    }

    private function getDefaultPaths()
    {
        return[
            '/me*',
            '/auth*',
            '/dedicated*',
            '/vps*',
            '/order*',
            '/ip*',
            '/service*'
        ];
    }
}
