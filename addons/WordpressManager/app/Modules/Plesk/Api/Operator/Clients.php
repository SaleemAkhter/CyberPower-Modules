<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator;


use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\RestFullApi;

class Clients
{

    /**
     * @var RestFullApi
     */
    private $restFullApi;

    /**
     * Extension constructor.
     */
    public function __construct($restFullApi)
    {
        $this->restFullApi = $restFullApi;
    }

    public function domains($userId){
        $request =[];
        return $this->restFullApi->get("/clients/{$userId}/domains",$request);
    }
}