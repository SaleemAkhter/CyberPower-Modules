<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-08-20
 * Time: 13:14
 */

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers;


use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\CustomFields;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall\Rule;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\Smarty;
use stdClass;

class FirewallManager
{
    protected $params;


    public function __construct($params)
    {
         $this->params = $params;
    }
    public function getFirewall()
    {
        $api = new Api($this->params);
        return $api->firewall->all();
    }
    public function getFirewallByID($id){
        $api = new Api($this->params);
        return $api->firewall->one($id);
    }
    public function getClient(){
       return new Client($this->params);

    }
    public function getFirewallsList(){
        $firewallsID  = $this->getClient()->getFirewalls();
        if(!empty($firewallsID)){
            return $this->getAllFirewall($firewallsID);
        }

        return [];

    }
    public function getFirewallRules($firewallID){
        $firewall = $this->getFirewallByID($firewallID);
        if(!in_array($firewallID, $this->getClient()->getFirewalls())){
            return [];
        }

        return $this->prepareDataToTableRules($firewall);
    }
    public function prepareDataToTableRules($firewall){
        $rules =[];

        foreach($firewall->inboundRules as $rule){

            $rules[] = [
                'id' => md5(serialize($rule)),
                'name' => 'Inbound',
                'protocol' => strtoupper($rule->protocol),
                'ports' => $this->preparePrettyPort($rule),
                'sources' => $this->prepareSurcesField($rule->sources),
            ];
        }

        foreach($firewall->outboundRules as $rule){
            $rules[] = [
                'id' => md5(serialize($rule)),
                'name' => 'Outbound',
                'protocol' => strtoupper($rule->protocol),
                'ports' =>  $this->preparePrettyPort($rule),
                'sources' => $this->prepareSurcesField($rule->destinations),
            ];

        }

        return $rules;
    }

    private function preparePrettyPort($rule){
        if(strtoupper($rule->protocol) == "ICMP"){

            return'';
        } elseif ($rule->ports == 0){
            return 'ALL';
        }

        return $rule->ports;

    }
    private function prepareSurcesField($field){
            if(is_array($field->addresses)){
                $field->addresses = implode('<br />', $field->addresses);
            }
            return $field->addresses;

    }
    private function getAllFirewall($firewallsID){
        $allFirewalls = [];
        $api = new Api($this->params);
        foreach($firewallsID as $id){
            try{
                $allFirewalls[] = $api->firewall->one($id);
            }
            catch(\Exception $ex){
                $this->removeIDFromField($id);
                continue;
            }

        }
        return $this->preparePrettyTable($allFirewalls);
    }

    private function preparePrettyTable($firewalls)
    {
        $fieldsArray = [];
        $decodedFirewallPrefix = '';

        $encodedFirewallPrefix = $this->getClient()->getFirewallPrefix();
        if (!empty($encodedFirewallPrefix)) {
            $decodedFirewallPrefix = Smarty::get()->fetch($encodedFirewallPrefix, $this->params);
        }

        foreach ($firewalls as $one) {
            $fieldsArray[] = [
                'id' => $one->id,
                'name' => preg_replace('/' . $decodedFirewallPrefix . '/', '', $one->name, 1),
                'inboundRules' => count($one->inboundRules),
                'outboundRules' => count($one->outboundRules),
            ];
        }

        return $fieldsArray;
    }

    public function createFirewall($firewallName)
    {
        if (count($this->getClient()->getFirewalls()) >= $this->getClient()->getFirewallsLimit() && $this->getClient()->getFirewallsLimit() != "") {
            throw new \Exception(sprintf(Lang::getInstance()->T('firewallsLimit'), $this->getClient()->getFirewallsLimit()));
        }

        $encodedFirewallPrefix = $this->getClient()->getFirewallPrefix();

        if (!empty($encodedFirewallPrefix)) {
            $decodedFirewallPrefix = Smarty::get()->fetch($encodedFirewallPrefix, $this->params);
            $firewallName = $decodedFirewallPrefix . $firewallName;
        }

        $createParams = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall\Create();
        $createParams->setName($firewallName)
            ->setDropletIds([$this->params['customfields']['serverID']])
            ->setDefaultInboundRule()
            ->setOutboundRules([]);


        $api = new Api($this->params);
        $firewall = $api->firewall->createFirewall($createParams);

        $allFirewalls = $this->getClient()->getFirewalls();
        $allFirewalls[] = $firewall->id;

        CustomFields::set($this->params['serviceid'], 'firewalls', implode(',', $allFirewalls));
    }

    public function deleteFirewall($id){
        $allFirewall =  $this->getClient()->getFirewalls();
        if(in_array($id, $allFirewall)){
            $api = new Api($this->params);
            $api->firewall->delete($id);
            $this->removeIDFromField($id);
            return;

        }
        throw new \Exception(Lang::getInstance()->T('thisFirewallIsNotAssigned'));
    }

    private function removeIDFromField($id){
        $allFirewall = array_flip( $this->getClient()->getFirewalls());
        unset($allFirewall[$id]);
        $allFirewall = array_flip($allFirewall);
        CustomFields::set($this->params['serviceid'], 'firewalls', implode(',', $allFirewall));
    }

    public function createRule($firewallID, $data){
        $rule = new Rule();
        $rule->setType($data['type'])
            ->setProtocol($data['protocol'])
            ->setPort($data['port'])
            ->setSources(explode(',', $data['addresses']));


        $api = new Api($this->params);
        $api->firewall->addRule($firewallID, $rule);
    }
    public function editRule($firewallID,$data)
    {
        $firewall = $this->getFirewallByID($firewallID);

        $inbound = [];
        $outbound = [];

        foreach ($firewall->inboundRules as $inboundRule)
        {
            if(md5(serialize($inboundRule)) !== $data['id'])
            {
                $inbound[] = $inboundRule;
            }
        }

        foreach ( $firewall->outboundRules as $outboundRule )
        {
            if ( md5(serialize($outboundRule)) !== $data['id'] )
            {
                $outbound[] = $outboundRule;
            }
        }

        $newRule = new stdClass();

        $newRule->protocol = $data['protocol'];
        $newRule->ports    = $data['port'];

        $this->checkIfIpsAreCorrect($data['addresses']);
        
        if($data['type'] === 'inbound_rules')
        {
            $newRule->sources = new stdClass();
            $newRule->sources->addresses = explode(',', $data['addresses']);
            $inbound[]        = $newRule;
        }
        else
        {
            $newRule->destinations = new stdClass();
            $newRule->destinations->addresses = explode(',', $data['addresses']);

            $outbound[] = $newRule;
        }

        $api = new Api($this->params);
        $inbound = $this->inboundRulesToArray($inbound);
        $outbound = $this->outboundRulesToArray($outbound);

        $dropletIds = [$this->params['customfields']['serverID']];

        return $api->firewall->editRule($firewall,$dropletIds,$inbound,$outbound);
    }
    
    private function checkIfIpsAreCorrect($addresses)
    {
        if($addresses == '0.0.0.0/0,::/0'){
            return;
        }
        
        $ips = explode(',', $addresses);
        foreach($ips as $ip)
        {   
            if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            {
                throw new \Exception(Lang::getInstance()->T('addressInvalid'));
            }          
        }
    }

    public function canUserCreateRule( $whmcsParams, $ruleType,$firewallID )
    {
        $firewall = $this->getFirewallByID($firewallID);

        $fieldDataProv = new FieldsProvider($whmcsParams['packageid']);

        $inboundLimit    = isset($whmcsParams['configoptions']['inboundRules']) ? (int)$whmcsParams['configoptions']['inboundRules'] : (int)$fieldDataProv->getField('inboundRulesLimit');
        $outboundLimit   = isset($whmcsParams['configoptions']['outboundRules']) ? (int)$whmcsParams['configoptions']['outboundRules'] : (int)$fieldDataProv->getField('outboundRulesLimit');
        $totalRulesLimit = isset($whmcsParams['configoptions']['totalRules']) ? (int)$whmcsParams['configoptions']['totalRules'] : (int)$fieldDataProv->getField('totalRulesLimit');

        $inboundRulesCount  = count($firewall->inboundRules);
        $outboundRulesCount = count($firewall->outboundRules);
        $totalRulesCount    = count($firewall->inboundRules) + count($firewall->outboundRules);

        if ( $totalRulesLimit > -1 && $totalRulesCount >= $totalRulesLimit )
        {
            return false;
        }

        if ( $ruleType == 'inbound_rules' && $inboundLimit > -1 && $inboundRulesCount >= $inboundLimit )
        {
            return false;
        }

        if ( $ruleType == 'outbound_rules' && $outboundRulesCount > -1 && $outboundRulesCount >= $outboundLimit )
        {
            return false;
        }

        return true;
    }

    public function deleteRule($firewallID, $ruleID){
        $rule = $this->findAndGetRule($firewallID, $ruleID);
        if(is_null($rule)){
            throw new \Exception(Lang::getInstance()->T('unableToFindRule'));
        }
        $api = new Api($this->params);

        $api->firewall->deleteRule($firewallID, $rule);

    }

    public function findAndGetRule($firewallID, $ruleID){
        $firewall = $this->getFirewallByID($firewallID);

        return $this->getRuleForPosition($firewall, $ruleID);

    }
    private function getRuleForPosition($firewall, $position){

        foreach($firewall->outboundRules as $value) {
            if($position == md5(serialize($value))){
                if(!$value->ports && $value->protocol === 'icmp'){
                    $value->ports = '';
                }
                elseif (!$value->ports)
                {
                    $value->ports = 'all';
                }
                return  ['outbound_rules' => [$value]];
            }
        }
        foreach($firewall->inboundRules as $value) {
            if($position == md5(serialize($value))){
                if(!$value->ports && $value->protocol === 'icmp'){
                    $value->ports = '';
                }
                elseif (!$value->ports)
                {
                    $value->ports = 'all';
                }
                return ['inbound_rules' => [$value]];
            }
        }

        return null;

    }

    private function inboundRulesToArray( $rules )
    {
        $out = [];
        foreach ( $rules as $rule )
        {
            $temp = [
                'protocol' => $rule->protocol,
                'ports'    => $rule->ports ?: 'all',
                'sources'  => ['addresses'=>$rule->sources->addresses]
            ];
            if($temp['ports'] === 'all' && $temp['protocol'] === 'icmp')
            {
                $temp['ports'] = '';
            }

            $out[] = $temp;
        }
        return $out;
    }

    private function outboundRulesToArray( $rules )
    {
        $out = [];
        foreach ( $rules as $rule )
        {
            $temp = [
                'protocol'     => $rule->protocol,
                'ports'        => $rule->ports ?: 'all',
                'destinations' => ['addresses'=>$rule->destinations->addresses]
            ];
            if($temp['ports'] === 'all' && $temp['protocol'] === 'icmp')
            {
                $temp['ports'] = '';
            }

            $out[] = $temp;
        }
        return $out;
    }
}