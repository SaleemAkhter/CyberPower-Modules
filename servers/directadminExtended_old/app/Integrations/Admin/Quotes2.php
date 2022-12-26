<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Integrations\Admin;

use \ModulesGarden\Servers\DirectAdminExtended\Core\Hook\AbstractHookIntegrationController;

class Quotes2 extends AbstractHookIntegrationController
{
    /**
     * Quotes constructor.
     */
    public function __construct()
    {
        $this->addIntegration(
            'quotes',
            ['action' => 'manage'], // request params can be string/int or array if multiple values possible e.g. ['action' => ['manage', 'manage2']]
                                    //this is same as: $_REQUEST['action'] === 'manage' || $_REQUEST['action'] === 'manage2'
            [\ModulesGarden\Servers\DirectAdminExtended\App\Http\Admin\Quotes::class, 'index2'],
            '.tablebg',
            self::TYPE_CUSTOM,
            'mgPrepareQuoteItems'
        );
    }
}
