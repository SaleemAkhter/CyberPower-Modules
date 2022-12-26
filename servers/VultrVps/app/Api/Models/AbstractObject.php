<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;

trait AbstractObject
{

    /**
     * @var ApiClient
     */
    protected $apiClient;
    protected $path;

    /**
     * @return ApiClient
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * @param ApiClient $apiClient
     * @return AbstractObject
     */
    public function setApiClient($apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return AbstractObject
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function toArray(){
        $data = call_user_func('get_object_vars', $this);
        unset($data['apiClient'], $data['path']);
        return $data;
    }

    public function delete()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException(get_called_class() ." Id cannot be empty");
        }
        if (is_null($this->path))
        {
            throw new \InvalidArgumentException(get_called_class() ." path cannot be empty");
        }
        return $this->apiClient->delete($this->path);
    }

}