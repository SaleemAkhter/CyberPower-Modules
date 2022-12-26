<?php

namespace ModulesGarden\DirectAdminExtended\Core\Hook;

class HookManager
{
    protected $hookRegister       = [];
    protected static $currentName = "";
    protected $files              = [];

    /**
     * @var Config
     */
    protected $config;

    public function __construct($dir)
    {
        $this->config = new Config();
        $path         = $dir . DS . "app" . DS . "Hooks";
        $files        = scandir($path, 1);
        
        if (count($files) != 0)
        {
            foreach ($files as $key => &$value)
            {
                if ($value === "." || $value === ".." || is_dir($path . DIRECTORY_SEPARATOR . $value))
                {
                    unset($files[$key]);
                }
            }
        }
        
        $this->files = $files;
    }

    public static function create($dir)
    {
        $hookManager = new HookManager($dir);

        foreach ($hookManager->getFiles() as $file)
        {
            $path = $dir . DS . "app" . DS . "Hooks" . DS . $file;
            try
            {
                HookManager::$currentName = explode(".", $file)[0];
                require $path;
            }
            catch (\Exception $e)
            {
                ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage() . " ||||HookPath: {$path}", $e->getTrace());
            }
        }

        $hookManager->start();
        return $hookManager;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function register($callback, $sort = 1)
    {

        $this->hookRegister[] = [
            "name"     => HookManager::$currentName,
            "function" => $callback,
            "sort"     => $sort
        ];
    }

    protected function start()
    {
        foreach ($this->hookRegister as $hook)
        {
            if ($this->config->checkHook($hook['name']))
            {
                add_hook(
                        $hook['name'], $hook['sort'], $hook['function']
                );
            }
        }
    }
}
