<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\Core\ModuleConstants;

/**
 * Description of CartTotals
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class CartTotals
{
    private $requreFiles = [
        'cartfunctions',
        'clientfunctions',
        'domainfunctions',
        'configoptionsfunctions',
        'invoicefunctions',
        'orderfunctions'
    ];

    public function __construct()
    {
        $fullPath = ModuleConstants::getFullPathWhmcs('includes');

        foreach ($this->requreFiles as $fileName)
        {
            ModuleConstants::requireFile($fullPath . DS . $fileName . ".php");
        }
    }

    public function getCartInfo()
    {
        $calcCartTotals = calcCartTotals();
        foreach ($calcCartTotals['addons'] as $key => &$addon)
        {
            $calcCartTotals['addons'][$key]['pricingtext'] = (string) $addon['pricingtext'];
        }
        return $calcCartTotals;
    }
}
