<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Validator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\File;

/**
 * Description of CronManager
 *
 * @author Rafał
 */
class CronManager {
    
    const AVAILABLE_MODES = ['cli', 'cgi-fcgi'];
    
    protected $arguments = [];
    
    protected $appCronPath = '';
    
    protected $coreCronPath = '';
    
    protected $cronPids = '';
    
    protected $isParent = true;
    
    protected $parentId;
    
    protected $childId;

    /**
     * @var Validator
     */
    protected $fileValidator;
    
    public function __construct(Validator $fileValidator) {
        $this->fileValidator = $fileValidator;
        $this->appCronPath   = ModuleConstants::getModuleRootDir() . DS . 'app' . DS . 'Cron' . DS;
        $this->coreCronPath  = ModuleConstants::getModuleRootDir() . DS . 'core' . DS . 'Cron' . DS;
        $this->cronPidsPath  = ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'crons' . DS;
        
        if($this->fileValidator->isExist($this->cronPidsPath) === false)
        {
            mkdir($this->cronPidsPath);
            File::setPermission($this->cronPidsPath);
        }

        if (self::isThread())
        {
            $this->appCronPath  .= "Thread" . DS;
            $this->coreCronPath .= "Thread" . DS;
        }
        else 
        {
            $this->appCronPath  .= "WithoutThread" . DS;
            $this->coreCronPath .= "WithoutThread" . DS;
        }
        
    }
    
    public function setArgv($arguments)
    {
        $this->arguments = $arguments;
        
        return $this;
    }
    
    public function execute()
    {
        $this->loadArguments();
        
        if($this->isInCliMode() === false)
        {
            return $this;
        }
        
        foreach ($this->arguments as $className)
        {
            $class = ModuleConstants::getRootNamespace() . "\\";
            if ($this->fileValidator->isExist($this->appCronPath . $className . ".php"))
            {
                $class .= "App\Cron\\" . $this->getType() . "\\" . $className;
                $this->runTask($class, $className);
            }
            elseif ($this->fileValidator->isExist($this->coreCronPath . $className . ".php"))
            {
                $class .= "Core\Cron\\" . $this->getType() . "\\" . $className;
                $this->runTask($class, $className);
            }
        }
        
        return $this;
    }
    
    protected function runTask($class, $className)
    {
        if ($this->classExist($class) === true && $this->isCronRunning($className) === false)
        {
            $instant = DependencyInjection::create($class, null, (class_exists(\Thread::class)?false:true));
            
            $instant->setCronManager($this);
            if (self::isThread() === false)
            {
                $instant->ischild(!$this->isParent)
                        ->setChildId($this->childId)
                        ->setParentId($this->parentId);
            }
            $instant->setClassName($className);
            $instant->start();
        }
    }
    
    protected function getType()
    {
        return (self::isThread()?'Thread':'WithoutThread');
    }
    
    public static function isThread()
    {
        return class_exists(\Thread::class);
    }
    
    protected function loadArguments()
    {
        $deleteClass = [];
        foreach ($this->arguments as $key => $name) {
            if (strpos($name, 'cron.php') !== false)
            {
                $deleteClass[] = $key; 
            }
            elseif ($name === '--parent')
            {
                $this->parentId = $this->arguments[$key + 1];
                $this->isParent = false;
                $deleteClass[] = $key; 
                $deleteClass[] = $key + 1; 
            }
            elseif ($name === '--child')
            {
                $this->childId = $this->arguments[$key + 1];
                $this->isParent = false;
                $deleteClass[] = $key; 
                $deleteClass[] = $key + 1; 
            }
        }
        
        foreach ($deleteClass as $clasKey)
        {
            unset($this->arguments[$clasKey]);
        }
        
        return $this;
    }
    
    protected function classExist($name)
    {
        return class_exists($name);
    }
    
    public static function updatePids($name, $id = '')
    {
        touch(ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'crons' . DS . $name. 'Pid' . $id . '.php');
    }
    
    public static function removePids($name, $id = '')
    {
        unlink(ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'crons' . DS . $name. 'Pid' . $id . '.php');
    }
    
    public static function existPids($name, $id = '')
    {
        return file_exists(ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'crons' . DS . $name. 'Pid' . $id . '.php');
    }


    public function createPids($file)
    {
        return file_put_contents($file, '<?php die(); '.getmypid());
    }
    
    public function isChildRunning($name, $id)
    {
        $file = $this->cronPidsPath . $name. 'Pid' . $id . '.php';
        
        if($this->fileValidator->isExist($file) && filemtime($file) > time() - 7200)
        {
            return true;
        }
        return false;
    }
    
    private function isCronRunning($name)
    {
        if ($this->isParent === true)
        {
            $file = $this->cronPidsPath . $name. 'Pid.php';
            
            if($this->fileValidator->isExist($file) && filemtime($file) > time() - 7200)
            {
                return true;
            }

            if($this->createPids($file) === false)
            {
                return true;
            }

            return false;
        }
        else
        {
            $file = $this->cronPidsPath . $name. 'Pid' . $this->childId . '.php';
        
            if($this->fileValidator->isExist($file) && filemtime($file) > time() - 7200)
            {
                return true;
            }

            if($this->createPids($file) === false)
            {
                return true;
            }

            return false;
        }
        
        
        /**
        if(PHP_OS == "Linux")
        {
            $out = null;
            exec("ps aux | grep 'php -q.*modules/addons/AdvancedBilling/cron/cron.php$' | grep -v 'grep'", $out);
            if(count($out) > 1)
            {
                return true;
            }
        }
        **/
    }

    
    private function isInCliMode()
    { 
        if (in_array(php_sapi_name(), self::AVAILABLE_MODES)) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

}
