<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Admin;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\ApiCredentials;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages\ScheduledTasks;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\AwsEc2\Core\Http\AbstractController;
use ModulesGarden\Servers\AwsEc2\Core\Helper;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\WhmcsParams;

/**
 * ServicePageIntegration controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ServicePageIntegration extends AbstractController
{
    use WhmcsParams;

    public function index()
    {
        if ($this->getWhmcsParamByKey('status') === 'Active')
        {
            return Helper\viewIntegrationAddon()
                ->addElement(StatusWidget::class)
                ->addElement(ScheduledTasks::class);
        }
    }
}
