<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


class FirewallGroup
{
    use AbstractObject;

    protected $id; //String
    protected $description; //String
    protected $dateCreated; //Date
    protected $dateModified; //Date
    protected $instanceCount; //int
    protected $ruleCount; //int
    protected $maxRuleCount;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FirewallGroup
     */
    public function setId($id)
    {
        if(!$id){
            throw new \InvalidArgumentException("FirewallGroup 'id' - cannot be empty");
        }
        $this->path = sprintf("firewalls/%s", $id);
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return FirewallGroup
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     * @return FirewallGroup
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param mixed $dateModified
     * @return FirewallGroup
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstanceCount()
    {
        return $this->instanceCount;
    }

    /**
     * @param mixed $instanceCount
     * @return FirewallGroup
     */
    public function setInstanceCount($instanceCount)
    {
        $this->instanceCount = $instanceCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRuleCount()
    {
        return $this->ruleCount;
    }

    /**
     * @param mixed $ruleCount
     * @return FirewallGroup
     */
    public function setRuleCount($ruleCount)
    {
        $this->ruleCount = $ruleCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxRuleCount()
    {
        return $this->maxRuleCount;
    }

    /**
     * @param mixed $maxRuleCount
     * @return FirewallGroup
     */
    public function setMaxRuleCount($maxRuleCount)
    {
        $this->maxRuleCount = $maxRuleCount;
        return $this;
    }

    public function create(){
        if(!$this->getDescription()){
            throw new \InvalidArgumentException("FirewallGroup 'description' - cannot be empty");
        }
        $response   = $this->apiClient->post("firewalls", ['description' => $this->getDescription()]);
        $this->apiClient->getJsonMapper()->map($response->firewall_group, $this);
        return $response;
    }

    public function firewallRules(){
        $response = $this->apiClient->get($this->path."/rules" );
        $entities = [];
        foreach ($response->firewall_rules as $entery){
            $entity = $this->apiClient->getJsonMapper()->map($entery, new FirewallRule());
            $entity->setApiClient($this->apiClient);
            $entities[] = $entity;
        }
        return  $entities;
    }

    /**
     * @return FirewallRule
     */
    public function firewallRule($id=null){
        $entity = new FirewallRule();
        $entity->setApiClient($this->apiClient);
        $entity->setFirewallGroupId($this->id);
        if(!is_null($id)){
            $entity->setId($id);
        }
        return $entity;
    }

    
}