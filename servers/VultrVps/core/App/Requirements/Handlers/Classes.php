<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Requirements\Handlers;

use ModulesGarden\Servers\VultrVps\Core\App\Requirements\Handler;
use ModulesGarden\Servers\VultrVps\Core\App\Requirements\HandlerInterface;
use ModulesGarden\Servers\VultrVps\Core\App\Requirements\Instances\Classes as ClassesInstance;

/**
 * Description of Files
 *
 * @author INBSX-37H
 */
class Classes extends Handler implements HandlerInterface
{
    protected $classList = [];

    public function __construct(array $classList = [])
    {
        $this->classList = $classList;
        
        $this->handleRequirements();
    }

    public function handleRequirements()
    {
        foreach ($this->classList as $record)
        {
            $this->handleRequirement($record);
        }
    }

    protected function handleRequirement($record)
    {
        $className = $record[ClassesInstance::CLASS_NAME];
        if ($className[0] !== '\\')
        {
            $className = '\\' . $className;
        }

        if (class_exists($className))
        {
            return null;
        }

        $this->addUnfulfilledRequirement('In order for the module to work correctly, it requires the :class_name: class.',
            ['class_name' => $className]);
    }
}
