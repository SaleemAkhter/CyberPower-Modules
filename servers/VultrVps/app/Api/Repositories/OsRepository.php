<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Repositories;


use ModulesGarden\Servers\VultrVps\App\Api\Models\Backup;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Snapshot;

class OsRepository extends AbstractRepository
{

    public function findNotName(array $names)
    {
        if(!$names){
            throw new \InvalidArgumentException("OS names cannot be empty");
        }
        $this->filters['notName'] = $names;
        return $this;
    }

    /**
     * @throws \JsonMapper_Exception
     */
    public function get()
    {
        if ($this->entities)
        {
            return $this->entities;
        }
        $response       = $this->apiClient->get("os");
        return  $this->parseResponse($response->os);
    }

    protected function parseResponse($response){
        $this->entities = [];
        foreach ($response as $entery)
        {
            if($this->filters['notName'] && in_array($entery->name, $this->filters['notName'])){
                continue;
            }
            $this->entities[] = $entery;
        }
        return $this->entities;
    }

}