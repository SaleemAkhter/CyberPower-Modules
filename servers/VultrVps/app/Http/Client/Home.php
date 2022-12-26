<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Client;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Pages\ScheduledTasks;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Pages\ServiceManagement;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\VultrVps\Core\Helper;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractClientController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {
            return;
        }
        return Helper\view()
            ->addElement(StatusWidget::class);
    }

    public function console()
    {
        $kvm  = (new InstanceFactory())->fromWhmcsParams()->details()->instance->kvm;
        $response = new RedirectResponse($kvm);
        $response->send();
    }
}
