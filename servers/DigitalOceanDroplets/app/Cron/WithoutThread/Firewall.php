<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Cron\WithoutThread;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine\AbstractCronControllerWithoutThread;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;

/**
 * Description of mail
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Firewall extends AbstractCronControllerWithoutThread
{

    public function run()
    {
        echo "Cron started \n";

        try
        {
            $mail = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\Firewall\FirewallTask();
            $mail->run();
        }
        catch (Exception $ex)
        {
            echo "Fatal Error: " . $ex->getMessage() . "\n";
            logModuleCall('DigitalOceanDroplets', 'cron', '', $ex->getMessage());
        }

        echo "Cron end \n";
        $this->removePid();
    }



}
