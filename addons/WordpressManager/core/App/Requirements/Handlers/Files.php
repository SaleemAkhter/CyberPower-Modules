<?php

namespace ModulesGarden\WordpressManager\Core\App\Requirements\Handlers;

use \ModulesGarden\WordpressManager\Core\App\Requirements\Instances\Files as FilesInstance;

/**
 * Description of Files
 *
 * @author INBSX-37H
 */
class Files implements \ModulesGarden\WordpressManager\Core\App\Requirements\HandlerInterface
{
    use \ModulesGarden\WordpressManager\Core\Traits\Lang;
    
    protected $fileList = [];
    
    protected $unfulfilledRequirements = [];

    public function __construct(array $fileList = [])
    {
        $this->fileList = $fileList;
        
        $this->handleRequirements();
    }

    public function handleRequirements()
    {
        foreach ($this->fileList as $record)
        {
            if (!$this->isValidPath($record[FilesInstance::PATH]))
            {
                continue;
            }

            $this->handleRequirement($record);
        }
    }
    
    public function isValidPath($path)
    {
        if (stripos($path, FilesInstance::WHMCS_PATH) === 0
            || stripos($path, FilesInstance::MODULE_PATH) === 0)
        {
            return true;
        }
        
        return false;
    }
    
    protected function handleRequirement($record)
    {
        $filePath = $this->getFullPath($record[FilesInstance::PATH]);

        switch ($record[FilesInstance::TYPE])
        {
            case FilesInstance::REMOVE:
                $this->removeFile($filePath);
                break;
        }
        
    }
    
    public function getFullPath($recordPath = null)
    {
        if (stripos($recordPath, FilesInstance::WHMCS_PATH) === 0)
        {
            return str_replace(FilesInstance::WHMCS_PATH, \ModulesGarden\WordpressManager\Core\ModuleConstants::getFullPathWhmcs(),
                    str_replace('/', DIRECTORY_SEPARATOR, $recordPath));
        }

        if (stripos($recordPath, FilesInstance::MODULE_PATH) === 0)
        {
            return str_replace(FilesInstance::MODULE_PATH, \ModulesGarden\WordpressManager\Core\ModuleConstants::getModuleRootDir(),
                    str_replace('/', DIRECTORY_SEPARATOR, $recordPath));
        }

        return null;
    }
    
    protected function removeFile($filePath = null)
    {
        $fileValidator = new \ModulesGarden\WordpressManager\Core\FileReader\PathValidator();
        if (!$fileValidator->pathExists($filePath))
        {
            return null;
        }
        
        unlink($filePath);
        
        if (!$fileValidator->pathExists($filePath))
        {
            return null;
        }

        $this->addUnfulfilledRequirement('In order for the module to work correctly, please remove the following file: :remove_file_requirement:',
                $filePath);
    }
    
    protected function addUnfulfilledRequirement($message = null, $path = null)
    {
        if ($message && $path)
        {
            $this->loadLang();
            
            $this->unfulfilledRequirements[] = str_replace(':remove_file_requirement:', $path,
                    $this->lang->absoluteTranslate('unfulfilledRequirement', $message));
        }
    }
    
    public function getUnfulfilledRequirements()
    {
        return $this->unfulfilledRequirements;
    }
}
