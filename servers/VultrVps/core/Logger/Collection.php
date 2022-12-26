<?php

namespace ModulesGarden\Servers\VultrVps\Core\Logger;

use ModulesGarden\Servers\VultrVps\Core\Http\Request;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

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
