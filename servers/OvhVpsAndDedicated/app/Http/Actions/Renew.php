<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;

class Renew extends AddonController
{
    use WhmcsParams;

    public function execute($params = null)
    {
        try
        {
            $fieldsProvider            = new FieldsProvider($params['pid']);
            $api = (new OvhApiFactory())->formParams();
            $serverName =  $params['customfields']['serverName'];
            $api = (new OvhApiFactory())->formParams();
            //get service id
            $serviceInfo = $api ->get(sprintf('/vps/%s/serviceInfos',$serverName));
            //renew
            $response = $api ->get(sprintf('/service/%s/renew',$serviceInfo['serviceId']));
            //get services
            $services = [];
            foreach ($response as $item){
                foreach ($item['strategies'] as $strategy){
                    foreach ($strategy['servicesDetails'] as $service)
                        if($service['serviceId']==$serviceInfo['serviceId']){
                            $services = $strategy['services'];
                            break;
                        }
                }
            }
            //execute renew
            $result =  $api->post(sprintf('/service/%s/renew',$serviceInfo['serviceId']),[
                'dryRun' => false, // Indicates if renew order is generated (type: boolean)
                'duration' => sprintf('P%sM', $fieldsProvider->getField('vpsDuration')), // Renew duration (type: string)
                'services' => $services, // List of services to renew (type: long[])
            ]);
            if($fieldsProvider->getField('autoPayWithPreferredMethod')!='on'){
                return 'success';
            }
            //order id
            $orderId = $result['orderId'];
            if(!$orderId){
                throw new \Exception("Order id not found");
            }
            //me/payment/method
            $defaultPayment = current((array)$api ->get("/me/payment/method",['default' => true]));
            if(!$defaultPayment){
                throw new \Exception("Default Payment not found");
            }
            //me/order/{order id}/pay
            $payResponse =  $api->post(sprintf('/me/order/%s/pay',$orderId),[
                'paymentMethod' => ["id" => $defaultPayment]
            ]);
            return 'success';
        }catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

}