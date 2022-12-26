<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Requirements;

/**
 * Description of Handler
 *
 * @author INBSX-37H
 */
class Checker
{
    /** 
     * \ModulesGarden\Servers\DirectAdminExtended\Core\FileReader\PathValidator
     * @var type null|\ModulesGarden\Servers\DirectAdminExtended\Core\FileReader\|\ModulesGarden\Servers\DirectAdminExtended\Core\FileReader\Directory
     */
    protected $directoryHandler = null;
    
    protected $requirementsList = [];
    
    protected $checkResaults = [];
    
    /** 
     * Paths for Requirements class to be placed in
     * @var type array
     */
    const PATHS = [
        'app' . DIRECTORY_SEPARATOR . 'Configuration' . DIRECTORY_SEPARATOR . 'Requirements',
        'core' . DIRECTORY_SEPARATOR . 'Configuration' . DIRECTORY_SEPARATOR . 'Requirements'
    ];
 
    public function __construct()
    {
        $this->directoryHandler = new \ModulesGarden\Servers\DirectAdminExtended\Core\FileReader\Directory();

        $this->loadRequirements();
        
        $this->checkRequirements();
    }
    
    protected function loadRequirements()
    {
        foreach (self::PATHS as $path)
        {
            $this->loadClassesByPath($path);
        }
    }
    
    protected function loadClassesByPath($path)
    {
        $fullPath = \ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants::getModuleRootDir() . DIRECTORY_SEPARATOR . $path;
        $files = $this->directoryHandler->getFilesList($fullPath, '.php', true);
        $classNamespace = $this->getClassNamespaceByPath($path);
        foreach ($files as $file)
        {
            $className = $classNamespace . $file;
            if (!class_exists($className) || !is_subclass_of($className, RequirementInterface::class))
            {
                continue;
            }
            
            $this->requirementsList[] = $className;
        }
    }
    
    protected function getClassNamespaceByPath($path)
    {
        $contextParts = explode('\\', self::class);
        $coreParts = explode(DIRECTORY_SEPARATOR, $path);

        $allParts = array_merge([$contextParts[0], $contextParts[1]], $coreParts);
        array_walk($allParts, function(&$item){
            $item = ucfirst($item);
        });
        
        return '\\' . implode('\\', $allParts) . '\\';
    }
    
    protected function checkRequirements()
    {
        foreach ($this->requirementsList as $requirement)
        {
            $instance = \ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection\DependencyInjection::call($requirement);
            $handler = $instance->getHandlerInstance();
            
            $this->checkResaults = array_merge($this->checkResaults, $handler->getUnfulfilledRequirements());
        }
    }
    
    public function getUnfulfilledRequirements()
    {
        return $this->checkResaults;
    }
}
