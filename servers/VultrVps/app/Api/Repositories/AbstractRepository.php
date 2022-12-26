<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Repositories;


use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;

abstract class AbstractRepository
{

    /**
     * @var ApiClient
     */
    protected $apiClient;
    protected $filters = [];
    protected $entities;
    protected $limit;


    /**
     * FloatingIpRepository constructor.
     * @param ApiClient $apiClient
     */
    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     * @return FloatingIpRepository
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


    public function count()
    {
        if (!$this->entities)
        {
            $this->get();
        }
        return count($this->entities);
    }

    abstract public function get();
}