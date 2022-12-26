<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Integrations\Admin;

use ModulesGarden\OvhVpsAndDedicated\Core\Hook\AbstractHookIntegrationController;

/**
 * Class ServerAdditionalOptions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerAdditionalOptions extends AbstractHookIntegrationController
{
    /**
     * ProductEdit constructor.
     */
    public function __construct()
    {
        $this->addIntegration(
            'configservers',
            ['action' => 'manage'],
            [\ModulesGarden\OvhVpsAndDedicated\App\Http\Admin\WhmcsServerAdditionalOptions::class, 'index'],
            '#mg-server-integration-wrapper',
            self::TYPE_CUSTOM,
            'ovhServerAdditionalOptions'
        );
    }
}
