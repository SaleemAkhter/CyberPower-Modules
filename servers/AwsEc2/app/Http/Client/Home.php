<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Client;

use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages\ScheduledTasks;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\AwsEc2\Core\Http\AbstractClientController;
use ModulesGarden\Servers\AwsEc2\Core\Helper;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\WhmcsParams;

/**
 * Description of Home
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractClientController
{
    use WhmcsParams;

    public function index()
    {
        $settingRepo = new Repository();
        $productInfo = $settingRepo->getProductSettings($this->getWhmcsParamByKey('packageid'));

        if ($this->getWhmcsParamByKey('status') === 'Active')
        {
            if($productInfo['hideScheduledTasks'] === 'off')
            {
                return Helper\view()
                    ->addElement(StatusWidget::class)
                    ->addElement(ScheduledTasks::class);
            }
            return Helper\view()
                ->addElement(StatusWidget::class);
        }
    }
}
