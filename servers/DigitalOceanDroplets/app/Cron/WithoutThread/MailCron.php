<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Cron\WithoutThread;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\CommandLine\AbstractCronControllerWithoutThread;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;

/**
 * Description of mail
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class MailCron extends AbstractCronControllerWithoutThread
{

    public function run()
    {
        echo "Cron started \n";
        foreach (self::getAllProducts() as $product)
        {
            try
            {
                echo "Start checking product #" . $product->id . " name: " . $product->name . "\n";
                $mail = new MailPiping\Reader($product);
                $mail->read();
            }
            catch (Exception $ex)
            {
                echo "Fatal Error: " . $ex->getMessage() . "\n";
                logModuleCall('DigitalOceanDroplets', 'cron', 'Product: #' . $product->id . ' Name: ' . $product->name, $ex->getMessage());
            }
        }
        echo "Cron end \n";
        $this->removePid();
    }

    private static function getAllProducts()
    {
        return Product::where('servertype', 'DigitalOceanDroplets')->get();
    }

}
