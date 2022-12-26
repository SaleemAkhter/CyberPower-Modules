<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Cli;


use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\RestFullApi;

class Extension
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

    public function call( $request){
        return $this->restFullApi->post("/cli/extension/call", $request);
    }
}