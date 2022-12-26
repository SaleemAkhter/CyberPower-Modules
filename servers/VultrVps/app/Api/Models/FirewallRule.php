<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


class FirewallRule
{
    use AbstractObject;
    protected $id; //int
    protected $type; //String
    protected $action; //String
    protected $protocol; //String
    protected $port; //String
    protected $subnet; //String
    protected $subnetSize; //int
    protected $source; //String
    protected $notes;
    protected $firewallGroupId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FirewallRule
     */
    public function setId($id)
    {
        if(!$id){
            throw new \InvalidArgumentException("Firewall Rule 'id' - cannot be empty");
        }
        $this->path .= sprintf("/%s", $id);
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirewallGroupId()
    {
        return $this->firewallGroupId;
    }

    /**
     * @param mixed $firewallGroupId
     * @return FirewallRule
     */
    public function setFirewallGroupId($firewallGroupId)
    {
        if(!$firewallGroupId){
            throw new \InvalidArgumentException("Firewall Group 'id' - cannot be empty");
        }
        $this->path = sprintf("firewalls/%s/rules", $firewallGroupId);
        $this->firewallGroupId = $firewallGroupId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return FirewallRule
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return FirewallRule
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param mixed $protocol
     * @return FirewallRule
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     * @return FirewallRule
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubnet()
    {
        return $this->subnet;
    }

    /**
     * @param mixed $subnet
     * @return FirewallRule
     */
    public function setSubnet($subnet)
    {
        $this->subnet = $subnet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubnetSize()
    {
        return $this->subnetSize;
    }

    /**
     * @param mixed $subnetSize
     * @return FirewallRule
     */
    public function setSubnetSize($subnetSize)
    {
        $this->subnetSize = $subnetSize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return FirewallRule
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     * @return FirewallRule
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    } //String

    public function create(){
        $setting=[
            'ip_type' => $this->getType(),
            'protocol'=> $this->getProtocol(),
            'subnet'=> $this->getSubnet(),
            'subnet_size'=> $this->getSubnetSize(),
            'port'=> $this->getPort(),
            'source'=> $this->getSource(),
            'notes'=> $this->getNotes()
        ];
        $response   = $this->apiClient->post($this->path, $setting);
        $this->setId($response->firewall_rule->id);
        return $response;
    }

    public function update(){
        if(!$this->id){
            throw new \InvalidArgumentException("Firewall Rule 'id' - cannot be empty");
        }
        $setting=[
            'notes'=> $this->getNotes()
        ];
        return $this->apiClient->put($this->path, $setting);
    }
}