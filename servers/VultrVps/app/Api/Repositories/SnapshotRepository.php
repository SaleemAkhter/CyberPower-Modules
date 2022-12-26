<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Repositories;


use ModulesGarden\Servers\VultrVps\App\Api\Models\Snapshot;

class SnapshotRepository extends AbstractRepository
{

    public function findInstanceId($instanceId)
    {
        if(!$instanceId){
            throw new \InvalidArgumentException("Instance ID cannot be empty");
        }
        $this->filters['instance_id'] = $instanceId;
        return $this;
    }

    public function findId(array $ids)
    {
        if(empty($ids)){
            throw new \InvalidArgumentException("Snapshots IDs cannot be empty");
        }
        $this->filters['id'] = $ids;
        return $this;
    }

    /**
     * @return Snapshot[]
     * @throws \JsonMapper_Exception
     */
    public function get()
    {
        if ($this->entities)
        {
            return $this->entities;
        }
        $response       = $this->apiClient->get("snapshots");
        return  $this->parseResponse($response->snapshots);
    }

    protected function parseResponse($response){
        $this->entities = [];
        foreach ($response as $entery)
        {
            if($this->filters['id'] && !in_array($entery->id, $this->filters['id'])){
                continue;
            }
            if($this->filters['instance_id']){
                $ex = explode("#",$entery->description);
                if($ex[0] != $this->filters['instance_id']){
                    continue;
                }
                $entery->instanceId = $ex[0];
                $entery->description = $ex[1];
            }
            $entity = $this->apiClient->getJsonMapper()->map($entery, new Snapshot());
            $entity->setApiClient($this->apiClient);
            $this->entities[] = $entity;
        }
        return $this->entities;
    }

    public function delete(){
        $this->get();
        foreach ($this->entities as $entity){
            $entity->delete();
        }
    }

    /**
     * @param $id
     * @return Snapshot
     */
    public function find($id){
        if(empty($id)){
            throw new \InvalidArgumentException("Snapshot ID cannot be empty");
        }
        $response       = $this->apiClient->get("snapshots/".$id);
        $this->parseResponse($response);
        if(empty($this->entities[0])){
            throw new \Exception(sprintf("Snapshot with id #%s not found",$id));
        }
        return $this->entities[0];
    }
}