<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Hook;

use ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http\Integration;
use ModulesGarden\OvhVpsAndDedicated\Core\DependencyInjection;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Smarty;

/**
 *  class HookIntegratorView
 *  Prepares a views basing on /App/Integrations/Admin/ & /App/Integrations/Client controlers
 *  to be injected on WHMCS subpages
 */
class HookIntegratorView
{
    use RequestObjectHandler;
    use Lang;
    use Smarty;

    /**
     * @var null|string|\ModulesGarden\OvhVpsAndDedicated\Core\UI\View
     * integration data
     */
    protected $view = null;

    /**
     * @var null|string
     * HTML integration code
     */
    protected $HTMLData = null;

    public function __construct($view, $integration)
    {
        $this->view = $view;
    }

    /**
     * returns string/HTML integration code
     */
    public function getHTML()
    {
        $this->viewToHtml();

        return $this->HTMLData;
    }

    /**
     * transforms integration data to string to be integrated in WHMCS template
     */
    protected function viewToHtml()
    {
        if ($this->view instanceof \ModulesGarden\OvhVpsAndDedicated\Core\UI\View)
        {
            $resp = $this->view->getResponse();
            $integrationPageController = DependencyInjection::call(Integration::class);
            $integrationPageController->setControllerResult($resp);

            $this->HTMLData = $integrationPageController->execute();

            return true;
        }

        if (is_string($this->view))
        {
            $this->HTMLData = $this->view;

            return true;
        }

        $this->HTMLData = '';
    }
}
