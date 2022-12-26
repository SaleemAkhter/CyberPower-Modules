<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Admin;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Traits\OutputBuffer;
use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Pages\ApiCredentials;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\VultrVps\Core\Helper;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;

/**
 * ServicePageIntegration controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractController
{
    use WhmcsParams, OutputBuffer;

    public function index()
    {
        if ($this->getWhmcsParamByKey('status') === 'Active')
        {
            return Helper\viewIntegrationAddon()
                ->addElement(StatusWidget::class);
        }
    }

    public function console()
    {
        $this->cleanOutputBuffer();
        $kvm  = (new InstanceFactory())->fromWhmcsParams()->details()->instance->kvm;
        $response = new RedirectResponse($kvm);
        $response->send();
    }
}
