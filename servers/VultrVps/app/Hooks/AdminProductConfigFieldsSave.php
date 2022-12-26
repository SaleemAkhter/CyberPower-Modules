<?php

$hookManager->register(
    function ($args)
    {
        try
        {
            $configController = new  \ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\Addon\ConfigOptions();
            $configController->runExecuteProcess($args);
        }
        catch (\Exception $exc)
        {
            //do nothing on save
        }
    },
    100
);
