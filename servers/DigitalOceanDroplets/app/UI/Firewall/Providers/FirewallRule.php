<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\RulesHelper;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FirewallRule extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;

        if($this->getRequestValue('loadData') == 'editRuleButton')
        {
            $this->handleUpdateFormData();
        }
    }

    public function create()
    {
        try
        {
            $snapshotManager = new FirewallManager($this->whmcsParams);

            if(!$snapshotManager->canUserCreateRule($this->whmcsParams, $this->formData['type'], $this->getRequestValue('firewallid'))) throw new Exception('You reached limit of rules');

            $snapshotManager->createRule($this->getRequestValue('firewallid'), $this->formData);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('createFirewallRuleSuccess');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function delete()
    {
        try
        {
            $multipleDelete = $this->getRequestValue('massActions', []);
            if(!empty($multipleDelete))
            {
                foreach($multipleDelete as $firewallID)
                {
                    $this->deleteOneRule($firewallID);
                }

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('deleteFirewallRules');
            }
            else
                {
                $this->deleteOneRule($this->formData['id']);

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('deleteFirewallRule');
            }
        }
        catch (Exception $ex)
        {
            if(strpos($ex->getMessage(), 'must have at least one rule') !== false && !empty($multipleDelete))
            {
                return (new HtmlDataJsonResponse())->setStatusError()->addData('refreshState', 'firewallRulesTable')->setMessageAndTranslate('multipleFirewallRules');
            }

            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }


    private function deleteOneRule($ruleID)
    {
        $snapshotManager = new FirewallManager($this->whmcsParams);
        $snapshotManager->deleteRule($this->getRequestValue('firewallid'), $ruleID);

        return true;
    }

    public function update()
    {
        $firewallId = $this->getRequestValue('firewallid');
        $firewallMng = new FirewallManager($this->whmcsParams);

        $firewallMng->editRule($firewallId, $this->formData);
    }

    private function handleUpdateFormData()
    {
        $firewallMng = new FirewallManager($this->whmcsParams);

        $rule = $firewallMng->findAndGetRule($this->getRequestValue('firewallid'),$this->actionElementId);

        $this->data['type']['value'] = isset($rule['inbound_rules'])? 'inbound_rules' : 'outbound_rules';

        $this->data['protocol']['value'] = $rule[$this->data['type']['value']][0]->protocol;
        $this->data['port'] = $rule[$this->data['type']['value']][0]->ports?:'all';

        $name = isset($rule['inbound_rules'])? 'sources' : 'destinations';

        $this->data['addresses'] = isset($rule[$this->data['type']['value']][0]->$name->addresses)? implode(',',$rule[$this->data['type']['value']][0]->$name->addresses) : '';

        $this->data['apps']['value'] = $this->getAppNameByValues($this->data['protocol']['value'], $this->data['port']);
    }

    private function getAppNameByValues( $protocol, $ports )
    {
        $possibleValues = [
            'alltcp' => ['protocol' => 'tcp', 'ports' => 'all'],
            'alludp' => ['protocol' => 'udp', 'ports' => 'all'],
            'ssh'    => ['protocol' => 'tcp', 'ports' => '22'],
            'http'   => ['protocol' => 'tcp', 'ports' => '80'],
            'https'  => ['protocol' => 'tcp', 'ports' => '443'],
            'mysql'  => ['protocol' => 'tcp', 'ports' => '3306'],
            'dnstcp' => ['protocol' => 'tcp', 'ports' => '53'],
            'dnsudp' => ['protocol' => 'udp', 'ports' => '53'],
            'icmp'   => ['protocol' => 'icmp', 'ports' => '53']
        ];
        $value          = array_search(['protocol' => $protocol, 'ports' => $ports], $possibleValues);

        return $value ? : 'custom';
    }
}
