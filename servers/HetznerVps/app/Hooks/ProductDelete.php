<?php


/**
 * Description of ProductDelete
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
$hookManager->register(
    function ($args) {
        if (\ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Product::where('id', $args['pid'])->first()->servertype == "HetznerVps")
        {
            $productProvider = new \ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider($args['pid']);
            $productProvider->removeAll();
        }
        }
    );
