<?php


/**
 * Description of ProductDelete
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
$hookManager->register(
    function ($args) {
            if (\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product::where('id', $args['pid'])->first()->servertype == "Ovh")
            {
                $productProvider = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider($args['pid']);
                $productProvider->removeAll();
            }
        }
    );
