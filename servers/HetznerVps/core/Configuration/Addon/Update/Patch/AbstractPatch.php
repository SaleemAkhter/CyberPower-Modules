<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Update\Patch;

use ModulesGarden\Servers\HetznerVps\Core\Helper\DatabaseHelper;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;

/**
 * Description of AbstractPatch
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractPatch
{
    /**
     * @var DatabaseHelper 
     */
    protected $databaseHelper;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $versionName;

    protected $version;

    /**
     * @param DatabaseHelper $databaseHelper
     */
    public function __construct(DatabaseHelper $databaseHelper)
    {
        $this->databaseHelper = $databaseHelper;
        $this->path = ModuleConstants::getModuleRootDir() . DS . 'app' . DS . 'Database';
        $this->versionName = end(explode("\\",get_called_class()));
    }

    /**
     * @return bool
     */
    protected function runSchema()
    {
        return ($this->databaseHelper->performQueryFromFile($this->path . DS . $this->versionName . DS . 'schema.sql') === true)
                ?false
                :true;
    }

    /**
     * @return bool
     */
    protected function runData()
    {
        return ($this->databaseHelper->performQueryFromFile($this->path . DS . $this->versionName . DS . 'data.sql') === true)
                ?false
                :true;
    }

    public function setVersion($version = null)
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function execute()
    {
        
    }
}
