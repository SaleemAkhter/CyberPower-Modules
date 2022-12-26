<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Others\InfoWidget;

class CronInfo extends InfoWidget implements AdminArea
{
    protected $id = 'cronInfo';
    protected $name = 'cronInfo';
    protected $title = 'cronInfoTitle';

    public function initContent()
    {
        $cronPath = ModuleConstants::getModuleRootDir() . DIRECTORY_SEPARATOR . 'cron' . DIRECTORY_SEPARATOR . 'cron.php';
        $this->setMessage('php -q ' . $cronPath . ' queue', true);
    }
}
