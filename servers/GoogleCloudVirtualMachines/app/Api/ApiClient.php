<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;


class ApiClient
{

    /**
     * @var \Google_Client
     */
    protected $googleClient;

    /**
     * @var string
     */
    protected $project;

    /**
     * @return \Google_Client
     */
    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    /**
     * @param \Google_Client $googleClient
     * @return ApiClient
     */
    public function setGoogleClient($googleClient)
    {
        $this->googleClient = $googleClient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     * @return ApiClient
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

}