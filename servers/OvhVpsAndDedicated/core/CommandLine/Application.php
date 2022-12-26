<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\CommandLine;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Tests\Compiler\DInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{
    protected $dir          = '';

    /**
     * @override
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->loadCommandsControllers($this->getCommands());

        parent::run($input, $output);
    }

    /**
     * Get all available files
     * @return array
     */
    protected function getCommands()
    {
        $files      = glob(ModuleConstants::getFullPath('app', $this->dir).'/*.php');
        $commands   = [];

        foreach($files as $file)
        {
            $file   = substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1);
            $file   = substr($file, 0, strrpos($file, '.'));

            $commands[] = $file;
        }

        return $commands;
    }

    /**
     * Create new objects and add it
     * @param $commands
     */
    protected function loadCommandsControllers($commands)
    {

        foreach($commands as $command)
        {
            $class  = ModuleConstants::getRootNamespace().'\App\\'.$this->dir.'\\'.$command;

            $this->add(new $class);
        }
    }
}