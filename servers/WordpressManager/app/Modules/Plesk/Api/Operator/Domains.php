<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator;


use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\RestFullApi;

class Domains
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

    public function get($name=null){
        $request =[];
        if($name){
            $request['name'] = $name;
        }
        return $this->restFullApi->get("/domains",$request);
    }
}