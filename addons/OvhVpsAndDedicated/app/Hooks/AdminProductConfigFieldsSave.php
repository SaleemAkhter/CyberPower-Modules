<?php

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\ProductConfiguration;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Server;

/**
 * Description of AdminAreaFooterOutput
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
$hookManager->register(
        function ($args) {

        if (\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product::where('id', $args['pid'])->first()->servertype == Server::OVH)
        {

            $request = \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl('request');
            $requestFields = $request->get('packageconfigoption');
            if(empty($requestFields))
            {
                return;
            }

            foreach ($request->request->all() as $key => $value) //stupid VUE!!! []
            {
                if(stripos($key, "packageconfigoption_") === false)
                {
                    continue;
                }
                $newKey = substr($key, strlen("packageconfigoption_"), strlen($key));
                $requestFields[$newKey] = $value;
            }


            ProductConfiguration::where('product_id', $args['pid'])->delete();
            $productProvider = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider($args['pid']);
            $productProvider->saveAll($requestFields);

        }
}, 1001);
