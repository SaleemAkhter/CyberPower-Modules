<?php

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\Smarty;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleNamespace;

/**
 * Description of AdminAreaFooterOutput
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
$hookManager->register(
        function ($args)
{
    if ($args['filename'] == "configproducts" && $_GET['action'] == "edit")
    {

        $product = ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product::where('id', $_GET['id'])->first();

        if ($product->servertype == "DigitalOceanDroplets" && $_REQUEST['ajax'] == 1 && isset($_REQUEST['loadData']) && ModuleNamespace::validate($_REQUEST['namespace']))
        {
            \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl('adminProductPage')->execute();
            die();
        }
    }
    if ($args['filename'] == "clientsservices"){
        return '
            <script>
            $(\'#ModuleTerminate-Yes\').on(\'click\', function(){
               $(\'.lu-tooltip\').remove();
            });          
            </script>          
            ';
    }
}, 1001
);
