<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Cli\Extension;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\RestFullApi;

class Cli
{
    /**
     * @var RestFullApi
     */
    private $restFullApi;
    protected $operators=[];
    /**
     * Extension constructor.
     */
    public function __construct($restFullApi)
    {
        $this->restFullApi = $restFullApi;
    }


    /**
     * @return Extension
     */
    public function extension(){

        if(!isset($this->operators[__FUNCTION__])){
            $this->operators[__FUNCTION__] = new Extension($this->restFullApi);
        }
        return $this->operators[__FUNCTION__];
    }

    public function commands(){
        return $this->restFullApi->get("/cli/commands");
    }
}