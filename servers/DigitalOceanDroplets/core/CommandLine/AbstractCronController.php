<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine\CronManager;

/**
 * Description of AbstractCronController
 *
 * @author Rafał
 */
if (class_exists(\Thread::class))
{
    class AbstractCronController extends \Thread
    {
        protected $className;
        protected $cronManager;

        public function setCronManager($cronManager)
        {
            $this->cronManager = $cronManager;

            return $this;
        }

        public function setClassName($className)
        {
            $this->className = $className;

            return $this;
        }

        protected function updatePid()
        {
            CronManager::updatePids($this->className);
        }
    }
}
else
{
    class AbstractCronController
    {
        
    }
}
