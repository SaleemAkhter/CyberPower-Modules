<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Cron;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum\CustomField;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ConfigOptions;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\Orders;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\CustomFields\CustomFields;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\CommandLine\Command;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\di;


/**
 * Description of mail
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class MailCron extends Command
{
    /**
     * @var string
     */
    protected $name = 'mail:run';


    /**
     * Run your custom code
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        if (!function_exists('ModuleBuildParams'))
        {
            require_once ModuleConstants::getFullPathWhmcs('includes') . DIRECTORY_SEPARATOR . "modulefunctions.php";
        }
        $io->title('Synchronize orders: Starting');
        $i = 0;
        //get orders
        $h = (new Hosting())->getTable();
        $o = (new Orders())->getTable();
        $query = Orders::select("{$o}.*")
            ->leftJoin($h,"{$h}.id", "=", "{$o}.hosting_id")
            ->where("{$h}.domainstatus", 'Active');
        $orders =[];
        foreach ($query->get() as $item) {
            $i++;
            try {
                /** @var Orders $item */
                $output->writeln(sprintf("Synchronize order #%s (Hosting ID %s)",$item->order_id, $item->hosting_id));
                $param = \ModuleBuildParams($item->hosting_id);
                di('whmcsParams')->setParams($param);
                if($param['customfields'][CustomField::SERVER_NAME]){
                    $orders[  $param['customfields'][CustomField::SERVER_NAME]] = $item->toArray();
                    $output->writeln(sprintf("Order: %s is already assigned.", $item->order_id));
                    continue;
                }
                $api = (new OvhApiFactory())->formParams();
                $orderItems = $api->get(sprintf("/me/order/%s/details",$item->order_id));
                $domain = null;
                foreach ($orderItems as $orderItem){
                    $details = $api->get(sprintf("/me/order/%s/details/%s",$item->order_id, $orderItem));
                    if(!$details['domain'] || preg_match("/\*001/",$details['domain']) ){
                        $output->writeln(sprintf("Order: %s current in progress.", $item->order_id));
                        break;
                    }else{
                        $domain = $details['domain'];
                    }
                    if($details['domain']!="*"){//us
                        continue;
                    }
                    $operations = $api->get(sprintf("/me/order/%s/details/%s/operations",$item->order_id, $orderItem));
                    foreach ($operations as $operation){
                        $response = $api->get(sprintf("/me/order/%s/details/%s/operations/%s",$item->order_id, $orderItem, $operation));
                        if(isset($response['resource']['name']) && $response['resource']['name']){
                            $domain =  $response['resource']['name'];
                            break;
                        }
                    }

                }
                if(is_null($domain)){
                    continue;
                }
                $serverName = preg_replace('/-linux$/', '',$domain );
                CustomFields::set($item->hosting_id, CustomField::SERVER_NAME,   $serverName);
                $orders[  $serverName] = $item->toArray();
                $output->writeln(sprintf("Order: %s has been synchronized.", $item->order_id));
            }catch (\Exception $exception){
                $io->error($exception->getMessage());
            }
        }

        $output->writeln("");
        $io->success([
            sprintf("Synchronize orders: %s Entries Processed.", $i),
            "Synchronize orders: Done"
        ]);

        echo "Cron started \n";
        foreach ($this->getAllProducts() as $product)
        {
            try
            {
                echo sprintf("Start checking product #%s %s \n", $product->id, html_entity_decode( $product->name, ENT_QUOTES));
                $mail = new MailPiping\Reader($product);
                $mail->setOrders($orders);
                $mail->read();
            }
            catch (Exception $ex)
            {
                echo "Fatal Error: " . $ex->getMessage() . "\n";
                \logModuleCall('Ovh', 'cron', 'Product: #' . $product->id . ' Name: ' . $product->name, $ex->getMessage());
            }
        }
        echo "Cron end \n";
    }

    private function getAllProducts()
    {
        return Product::where('servertype', 'OvhVpsAndDedicated')->where('hidden',0)->get();
    }
}
