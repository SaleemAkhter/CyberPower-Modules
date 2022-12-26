<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers;

use LKDev\HetznerCloud\Models\Firewalls\Firewall;
use LKDev\HetznerCloud\Models\Firewalls\FirewallRule;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class RuleProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    /**
     * @var Api
     */
    private $api;
    private $rule;
    private $apply;
    private $values;
    private $firewall;

    private function getFirewallById($firewallId)
    {
        $manager           = new FirewallManager($this->getWhmcsParams());
        return $manager->getFirewall($firewallId);
    }

    public function read()
    {
        $this->availableValues['direction']           = [
            'in'  => 'IN',
            'out' => 'OUT'
        ];
        $this->availableValues['protocol']            = [
            'tcp'  => 'TCP',
            'udp'  => 'UDP',
            'icmp' => 'ICMP',
            'gre'  => 'GRE',
            'esp'  => 'ESP'
        ];
        $this->availableValues['ipSourceDestination'] = [
            'sourceIp'      => 'Source IP',
            'destinationIp' => 'Destination IP'
        ];

        $this->values = $this->actionElementId;

        if (!empty($this->formData))
        {
            $this->data = $this->formData;
        }

        if ($this->actionElementId === '0' || !empty($this->actionElementId))
        {
            $firewall                  = (array)$this->getFirewallById($this->request->get('firewallid'))->rules[$this->actionElementId];
            $firewall['sourceIp']      = implode(',', $firewall['sourceIPs']);
            $firewall['destinationIp'] = implode(',', $firewall['destinationIPs']);
            $firewall['ruleId']        = $this->values['ruleId'];
            $this->data                = array_merge($firewall, $this->data);
        }
    }

    public function reload()
    {
        parent::reload();
    }


    public function create()
    {
        try
        {
            $this->prepareFormData();

            if (!is_array($this->formData['sourceIp']) || !is_array($this->formData['destinationIp']))
            {
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('invalidIPorMaskSpecified');
            }

            $this->rule    = $this->newFirewallRule();
            $newRulesArray = $this->newRulesArray();

            if (!is_array($newRulesArray))
            {
                return $newRulesArray;
            }

            return $this->setRules($newRulesArray, 'CreateSuccessful');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function update()
    {
        if ($this->formData['direction'] == 'in')
        {
            $this->formData['destinationIp'] = [];
        }
        if ($this->formData['direction'] == 'out')
        {
            $this->formData['sourceIp'] = [];
        }
        $this->prepareFormData();
        $this->data                                   = ($this->getFirewallById($this->request->get('firewallid')));
        $this->rule                                   = $this->newFirewallRule();
        $this->data->rules[$this->formData['ruleId']] = $this->rule;
        return $this->setRules($this->data->rules, 'UpdateSuccessful');
    }

    public function delete()
    {
        $this->firewall = $this->getFirewallById($this->request->get('firewallid'));
        $this->unsetRuleArray($this->formData['ruleId']);
        $this->refreshRulesArray();
        return $this->updateRules('DeleteSuccessful');
    }

    public function deleteMass()
    {
        $this->firewall = $this->getFirewallById($this->request->get('firewallid'));
        $this->formData = $this->getMassActionsValues();
        foreach ($this->formData as $key => $value)
        {
            $this->unsetRuleArray($value);
        }
        $this->refreshRulesArray();
        return $this->updateRules('MassDeleteSuccessful');
    }

    private function updateRules($message)
    {
        $firewall = new Firewall($this->request->get('firewallid'));
        $result   = $firewall->setRules($this->getRulesArray());
        if ($result->error == null)
        {
            return (new HtmlDataJsonResponse())->setMessageAndTranslate($message);
        }
    }

    private function unsetRuleArray($id)
    {
        unset($this->firewall->rules[$id]);
    }

    private function refreshRulesArray()
    {
        $this->firewall->rules =
            array_slice($this->firewall->rules, 0);
    }

    private function getRulesArray()
    {
        $rules = [];
        foreach ($this->firewall->rules as $rule)
        {
            $rules[] = $rule;
        }
        return $rules;
    }

    private function prepareFormData()
    {
        if (empty($this->formData['port']))
        {
            $this->formData['port'] = '';
        }

        if (empty($this->formData['sourceIp']))
        {
            $this->formData['sourceIp']      = [];
            $this->formData['destinationIp'] = explode(',', preg_replace('/\s+/', '', $this->formData['destinationIp']));
            $this->formData['destinationIp'] = $this->validateIp($this->formData['destinationIp']);
        }
        else
        {
            $this->formData['destinationIp'] = [];
            $this->formData['sourceIp']      = explode(',', preg_replace('/\s+/', '', $this->formData['sourceIp']));
            $this->formData['sourceIp']      = $this->validateIp($this->formData['sourceIp']);
        }
    }

    private function validateIp($ipArray)
    {
        foreach ($ipArray as $key => $ip)
        {
            $ipAndMask = explode('/', $ip);

            if (filter_var($ipAndMask[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            {
                if (!isset($ipAndMask[1]))
                {
                    $ip .= '/32';
                }
                elseif (!((int)$ipAndMask[1] >= 0 && (int)$ipAndMask[1] <= 32))
                {
                    return false;
                }
            }
            elseif (filter_var($ipAndMask[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            {
                if (!isset($ipAndMask[1]))
                {
                    $ip .= '/128';
                }
                elseif (!((int)$ipAndMask[1] >= 0 && (int)$ipAndMask[1] <= 128))
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
            $ipArray[$key] = $ip;
        }
        return $ipArray;
    }

    private function newFirewallRule()
    {
        return new FirewallRule(
            $this->formData['direction'],
            $this->formData['protocol'],
            $this->formData['sourceIp'],
            $this->formData['destinationIp'],
            $this->formData['port']
        );
    }

    private function newRulesArray()
    {
        $this->firewall = $this->getFirewallById($this->request->get('firewallid'));
        $newRulesArray  = [];
        foreach ($this->firewall->rules as $rule)
        {
            $this->rule->ip = empty($this->rule->sourceIPs) ? $this->rule->destinationIPs : $this->rule->sourceIPs;
            if ($this->rule == $rule)
            {
                return (new HtmlDataJsonResponse())->setMessageAndTranslate('RuleAlreadyExist');
            }
            $newRulesArray[] = $rule;
        }
        $newRulesArray[] = $this->rule;
        return $newRulesArray;
    }

    private function setRules($rules, $message)
    {
        $firewall = new Firewall($_GET['firewallid']);
        $result   = $firewall->setRules($rules);
        if ($result->error == null)
        {
            return (new HtmlDataJsonResponse())->setMessageAndTranslate($message);
        }
    }

}