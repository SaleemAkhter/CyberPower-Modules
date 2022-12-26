<?php

namespace ModulesGarden\WordpressManager\Core\Hook;

use \ModulesGarden\WordpressManager\Core\ModuleConstants;

class HookIntegrationsWrapper
{
    use \ModulesGarden\WordpressManager\Core\Traits\IsAdmin;
    use \ModulesGarden\WordpressManager\Core\Traits\Lang;
    use \ModulesGarden\WordpressManager\Core\Traits\Smarty;

    protected $integrations = [];

    protected $templateName = 'integrationsWrapper';
    protected $templateDirectory = null;

    public function __construct ($integrations = [])
    {
        if (is_array($integrations))
        {
            $this->integrations = $integrations;
        }

        $this->templateDirectory = ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR
            . ($this->isAdmin() ? 'admin' : 'client' . DIRECTORY_SEPARATOR . 'default') . DIRECTORY_SEPARATOR;
    }

    public function getHtml()
    {
        foreach ($this->integrations as $key => $integration)
        {
            if (!$integration['htmlData'] || $integration ['htmlData'] === '' || !is_string($integration ['htmlData'])
                    || !$integration['integrationDetails'] || !is_object($integration ['integrationDetails']))
            {
                unset($this->integrations[$key]);
            }
        }

        if (!$this->integrations)
        {
            return null;
        }

        $smarty = $this->getSmarty();

        $integrationHtml = $smarty->view($this->templateName, $this->getWrapperData(), $this->templateDirectory);

        return $integrationHtml;
    }

    protected function getWrapperData()
    {
        return [
            'integrations' => $this->integrations
        ];
    }
}
