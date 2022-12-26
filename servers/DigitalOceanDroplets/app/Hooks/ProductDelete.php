<?php


/**
 * Description of ProductDelete
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
$hookManager->register(
    function ($args) {
        if (\ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product::where('id', $args['pid'])->first()->servertype == "DigitalOceanDroplets")
        {
            $productProvider = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider($args['pid']);
            $productProvider->removeAll();
        }
        }
    );
