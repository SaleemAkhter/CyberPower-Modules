<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Integrations\Admin;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Hook\AbstractHookIntegrationController;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestFormDataHandler;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;

class ProductEdit extends AbstractHookIntegrationController
{
    use RequestObjectHandler;
    /**
     * ProductEdit constructor.
     */
    public function __construct()
    {
//        die('asd');

//        $this->addIntegration(
//            'configproducts',
//            ['action' => 'edit'],
//            [\ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server\ProductPage::class, 'index'],
//            '#tblModuleSettings',
//            self::TYPE_APPEND,
//            null,
//            self::INSERT_TYPE_FULL
//        );

    }

}
