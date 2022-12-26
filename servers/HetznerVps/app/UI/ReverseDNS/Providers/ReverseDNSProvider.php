<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Providers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Helpers\ReverseDNSMenager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class ReverseDNSProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    public function read()
    {
        $values = stripslashes(html_entity_decode($this->actionElementId));
        $values = json_decode($values, true);

        $this->data['ip'] = $values['ip'];
        $this->data['dns_ptr'] = $values['dns_ptr'];
    }

    public function update()
    {
        $ip = $this->formData['ip'];
        $dns_ptr = $this->formData['dns_ptr'];
        $message = 'editReverseDNSSuccess';

        return $this->changeReverseDNS($ip, $dns_ptr, $message);
    }

    public function create()
    {
        $ip = $this->formData['prefix'].$this->formData['identifier'];
        $dns_ptr = $this->formData['dns_ptr'];
        $message = 'addReverseDNSSuccess';

        if(!$this->isIpAvailable($ip))
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('ipAlreadyUsed');
        }

        return $this->changeReverseDNS($ip, $dns_ptr, $message);
    }

    public function delete()
    {
        $ip = $this->formData['ip'];
        $dns_ptr = null;
        $message = 'resetReverseDNSSuccess';

        return $this->changeReverseDNS($ip, $dns_ptr, $message);
    }



    private function changeReverseDNS($ip, $dns_ptr, $message)
    {
        try
        {
            $api = new Api($this->getWhmcsParams());
            $serverId = $this->getWhmcsParams()['customfields']['serverID'];

            $result =  $api->server($serverId)->changeReverseDNS($ip, $dns_ptr);
            $response = $result->getResponsePart('action');

            if ($response->error == null)
            {
                return (new HtmlDataJsonResponse())->setMessageAndTranslate($message);
            }

        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }

    }

    public function isIpAvailable($ip)
    {
        $reverseDNSManager = new ReverseDNSMenager($this->getWhmcsParams());
        $server = $reverseDNSManager->get();
        $ipv6 = $server->publicNet->ipv6->dns_ptr;
        foreach((array)$ipv6 as  $reverseDNS)
        {
            $existIp = $reverseDNS->ip;

            if ($existIp == $ip)
            {
                return false;
            }
        }

        return true;
    }
}