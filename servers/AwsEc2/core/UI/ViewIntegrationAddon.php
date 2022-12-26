<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI;

/**
 * Integration Addon View Controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ViewIntegrationAddon extends View
{
    protected $wrapperTemplate = 'integrationAddon';
    protected $integration = true;

    public function __construct ($wrapperTemplate = null, $viewTemplate = null)
    {
        $this->setTemplate($viewTemplate);
        $this->mainContainer = new \ModulesGarden\Servers\AwsEc2\Core\UI\MainContainerIntegrationAddon();
        $this->initBreadcrumbs();
        $this->initCustomAssetFiles();
    }
}
