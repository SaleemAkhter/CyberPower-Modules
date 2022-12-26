<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine\CronManager;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Description of AbstractCronController
 *
 * @author Rafał
 */
abstract class AbstractCronControllerWithoutThread
{
    protected $exit = true;
    protected $isChild = false;
    protected $className;
    protected $child = 0;
    protected $childId;
    protected $parentId;
    protected $cronManager;

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function setChildId($childId)
    {
        $this->childId = $childId;

        return $this;
    }

    public function ischild($isChild)
    {
        $this->isChild = $isChild;

        return $this;
    }

    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    protected function updatePid()
    {
        CronManager::updatePids($this->className, $this->childId);

        if ($this->isChild && posix_getpgid($this->parentId) === false)
        {
            $this->removePid();
        }
    }
    
    protected function isExistPid()
    {
        return CronManager::existPids($this->className, $this->childId);
    }

    protected function removePid()
    {
        CronManager::removePids($this->className, $this->childId);
        exit;
    }

    abstract public function run();

    public function isChildrenCreate()
    {
        return false;
    }

    public function getChildrenCount()
    {
        return $this->child;
    }

    public function setCronManager($cronManager)
    {
        $this->cronManager = $cronManager;

        return $this;
    }
    
    public function getIndexElements()
    {
        $childrenListIds = [];
        
        for ($key = 1; $key <= $this->getChildrenCount(); $key++)
        {
            $childrenListIds[] = $key;
        }
        
        return $childrenListIds;
    }

    public function start()
    {
        if ($this->isStart() === false)
        {
            if ($this->child > 0 && $this->isChild === false)
            {
                $this->updatePid();
                while ($this->isExistPid())
                {
                    if ($this->isChildrenCreate())
                    {
                        $parentId = posix_getpid();
                        foreach ($this->getIndexElements() as $key)
                        {
                            if ($this->cronManager->isChildRunning($this->className, $key))
                            {
                                continue;
                            }

                            $phpInterpreter       = \PHP_BINARY ?: 'php';
                            $internalCronDumpFile = ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'crons' . DS . 'cronLog';
                            exec($phpInterpreter . " " . ModuleConstants::getModuleRootDir() . DS . 'cron' . DS . 'cron.php ' . $this->className . " --parent {$parentId} --child {$key} > {$internalCronDumpFile} &");
                        }
                    }

                    $this->updatePid();
                    $this->wait(2000000);
                }
                
                exit;
            }
            elseif ($this->child === 0 && $this->isChild === false)
            {
                $this->exit = false;
                $this->run();
            }
            elseif ($this->isChild === true)
            {
                $this->exit = false;
                $this->run();
            }
        }
    }

    public function isStart()
    {
        return ($this->exit === false);
    }

    public function wait($seconds = 500000)
    {
        usleep($seconds);
    }

    function __destruct()
    {
        $this->removePid();
    }
}
