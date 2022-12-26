<?php

/**
 * Description of AdminAreaFooterOutput
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
$hookManager->register(
        function ($args) {

        if (\ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Product::where('id', $args['pid'])->first()->servertype == "HetznerVps")
        {
            $productProvider = new \ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider($args['pid']);
            $productProvider->saveAll($_REQUEST['packageconfigoption']);
        }
}, 1001);
