<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Logger;

use function ModulesGarden\OvhVpsAndDedicated\Core\Helper\sl;
use ModulesGarden\OvhVpsAndDedicated\Core\Logger\Entity;
use ModulesGarden\OvhVpsAndDedicated\Core\Http\Request;

/**
 * Description of Collection
 *
 * @author inbs
 */
class Collection
{
    /**
     * @var LoggerModel
     */
    protected $model;
    
    /**
     * @var Request 
     */
    protected $requestObj;
    
    protected $limit = 10;
    protected $search;
    protected $sort;
    
    public function __construct(Request $requestObj)
    {
        $this->requestObj = $requestObj;
    }
    
    public function loadSearch()
    {
        
    }
    
    
    public function all()
    {
        
    }
    
    public function getBySearch($search)
    {
        
    }
    
    public function removeByDate($date)
    {
        
    }
    
    /**
     * @return Entity
     */
    protected function generatedEntityModel()
    {
        return sl(Entity::class);
    }
}
