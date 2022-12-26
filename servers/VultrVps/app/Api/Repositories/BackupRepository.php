<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Repositories;


use ModulesGarden\Servers\VultrVps\App\Api\Models\Backup;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Snapshot;

class BackupRepository extends AbstractRepository
{

    public function findIp($ip)
    {
        if(!$ip){
            throw new \InvalidArgumentException("IP Address cannot be empty");
        }
        $this->filters['ip'] = $ip;
        return $this;
    }

    public function findId(array $ids)
    {
        if(empty($ids)){
            throw new \InvalidArgumentException("Backup IDs cannot be empty");
        }
        $this->filters['id'] = $ids;
        return $this;
    }

    /**
     * @return Backup[]
     * @throws \JsonMapper_Exception
     */
    public function get()
    {
        if ($this->entities)
        {
            return $this->entities;
        }
        $response       = $this->apiClient->get("backups");
        return  $this->parseResponse($response->backups);
    }

    protected function parseResponse($response){
        $this->entities = [];
        foreach ($response as $entery)
        {
            if($this->filters['id'] && !in_array($entery->id, $this->filters['id'])){
                continue;
            }
            if($this->filters['ip'] && !preg_match("/{$this->filters['ip']}/", $entery->description)){
                continue;
            }
            $entity = $this->apiClient->getJsonMapper()->map($entery, new Backup());
            $entity->setApiClient($this->apiClient);
            $this->entities[] = $entity;
        }
        return $this->entities;
    }

    /**
     * @param $id
     * @return Backup
     */
    public function find($id){
        if(empty($id)){
            throw new \InvalidArgumentException("Backup ID cannot be empty");
        }
        $response       = $this->apiClient->get("backups/".$id);
        $this->parseResponse($response);
        if(empty($this->entities[0])){
            throw new \Exception(sprintf("Backup with id #%s not found",$id));
        }
        return $this->entities[0];
    }

    public function delete(){
        $this->get();
        foreach ($this->entities as $entity){
            $entity->delete();
        }
    }
}