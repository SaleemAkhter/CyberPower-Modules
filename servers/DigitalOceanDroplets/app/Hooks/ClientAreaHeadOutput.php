<?php

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\Smarty;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Description of ClientAreaHeadOutput
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
$hookManager->register(
        function ($args) {


            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController();
            if (!$pageController->clientAreaResetPassword()) {
                return '
                    <script type="text/javascript">
                    var windowAddress = String(window.location);
                    var hashTab = windowAddress.indexOf("#tabChangepw");
                    if (hashTab > -1) {
                        window.location = windowAddress.substring(0, hashTab)
                    }
                   </script>
                 ';
            }



}, 1001
);
