<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

use \ModulesGarden\WordpressManager\Core\ModuleConstants;

/**
 * Custom Js Code(per page) related functions
 * View Trat
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait CustomJsCode
{

    protected $customJsPath = null;    
    
    public function setCustomJsCode()
    {

        $path = $this->getCustomJsPath(debug_backtrace()[1]['class'], debug_backtrace()[1]['function']);
        if ($path)
        {
            $this->customJsPath = $path;
        }

        return $this;
    }

    protected function getCustomJsPath($class, $function)
    {
        $ctrlPos = stripos($class, '\App\Http\\');
        if ($ctrlPos)
        {
            $ctrl = substr($class, $ctrlPos + 10);
            $parts = explode('\\', $ctrl);
            $tmpType = $parts[0];
            unset($parts[0]);
            $parts[] = $function;
            array_walk($parts, function(&$value, $key){
                $value = lcfirst($value);
            });

            array_unshift($parts, 'app', 'UI', $tmpType, 'Templates');
            $file =  call_user_func_array([ModuleConstants::class, 'getFullPath'], $parts) . '.js';
            if (file_exists($file))
            {
                return $file;
            }

            return null;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCustomJsCode ()
    {
        if ($this->customJsPath && file_exists($this->customJsPath))
        {
            return file_get_contents($this->customJsPath);
        }
    }
}
