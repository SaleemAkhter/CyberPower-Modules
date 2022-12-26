<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'../../AwsEc2/app/Libs/AwsLib/vendor/autoload.php';
use Aws\AwsClient;
use Aws\Exception\AwsException;
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function elasticip_MetaData()
{
    return array(
        'DisplayName' => 'Amazon Elastic IP',
        'APIVersion' => '1.0', // Use API Version 1.1
        'RequiresServer' => true, // Set true if module requires a server to work

    );
}

function elasticip_ConfigOptions()
{
    return array(
    );
}

function elasticip_CreateAccount(array $params)
{

    try {
        if($params['serviceid']){
            $id=$params['serviceid'];
            $moduleInterface = new \WHMCS\Module\Server();
            $moduleInterface->loadByServiceID($id);
            $instanceService = $moduleInterface->buildParams();
            $region=(isset($instanceService['configoptions']['region']) && !empty($instanceService['configoptions']['region']))?$instanceService['configoptions']['region']:'us-west-3';
            $instanceId = $instanceService['model']->serviceProperties->get('InstanceId');
            $sharedConfig = [
                'service' => 'ec2',
                'region' => $region,
                'version' => '2016-11-15',
                'credentials' =>[
                    'key' => $params['serverusername'],
                    'secret' => $params['serverpassword']
                ]
            ];
            $client = new \Aws\AwsClient($sharedConfig);
            if($client){
                $instancesData = $client->describeInstances([
                    'InstanceIds' => [$instanceId]
                ]);

                $reservations = $instancesData->get('Reservations');
                $instanceData=$reservations[0]['Instances'][0];
                if($instanceData){

                    $instances = $client->describeSubnets([
                        'Filters' => [
                            [
                                'Name' => 'default-for-az',
                                'Values' => ['true']
                            ],
                            [
                                'Name' => 'availability-zone',
                                'Values' => [$instanceData['Placement']['AvailabilityZone']]
                            ]
                        ]
                    ]);
                    $subnets = $instances->get('Subnets');

                    $defaultSubnet=$subnets[0];
                    if (!$defaultSubnet)
                    {
                        $defaultSubnet = $client->createDefaultSubnetForAvZone();
                    }
                    // debug($defaultSubnet);die();
                    $deviceIndex = count($instanceData['NetworkInterfaces']);
                    $networkDevices = $instanceData['NetworkInterfaces'];
                    $networkDevice=false;
                    if($networkDevices){
                        $networkDevice=$networkDevices[0];
                    }

                    if($networkDevice){
                        $networkInterface = $networkDevice;
                        $ip = $client->allocateAddress([]);
                        $ipDetails = $ip->toArray();
                        // $client->associateAddress([
                        //     'AllocationId' => $ipDetails['AllocationId'],
                        //     'NetworkInterfaceId' => $networkInterface['NetworkInterfaceId']
                        // ]);
                        $params["model"]->serviceProperties->save(["Elastic IP" =>$ipDetails['PublicIp']]);
                        $params["model"]->serviceProperties->save(["Allocation Id" =>$ipDetails['AllocationId']]);

                        Capsule::table("AwsEc2_ElasticIps")->insert([
                            'service_id'=>$params['serviceid'],
                            'elastic_ip'=>$ipDetails['PublicIp'],
                            'allocation_id'=>$ipDetails['AllocationId']
                        ]);
                    }
                    logModuleCall("elasticip","allocate",$networkDevice,$ip);
                }
                return "success";
            }else{
                logModuleCall(
                    'elasticip',
                    __FUNCTION__,
                    $sharedConfig,
                    "Unable to connect to AWS",
                    ""
                );
                return "Failed";
            }
        }else{
            logModuleCall(
                'elasticip',
                __FUNCTION__,
                $params,
                "Unable to get parent service",
                ""
            );
            return "Failed";
        }
    } catch (Exception $e) {
        logModuleCall(
            'elasticip',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function elasticip_SuspendAccount(array $params)
{
    try {
        // Call the service's suspend function, using the values provided by
        // WHMCS in `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'elasticip',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function elasticip_UnsuspendAccount(array $params)
{
    try {
        // Call the service's unsuspend function, using the values provided by
        // WHMCS in `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'elasticip',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function elasticip_TerminateAccount(array $params)
{
    try {

        if($params['serviceid']){
            $id=$params['serviceid'];
            $moduleInterface = new \WHMCS\Module\Server();
            $moduleInterface->loadByServiceID($id);
            $instanceService = $moduleInterface->buildParams();
            $region=(isset($instanceService['configoptions']['region']) && !empty($instanceService['configoptions']['region']))?$instanceService['configoptions']['region']:'us-west-3';
            $instanceId = $instanceService['model']->serviceProperties->get('InstanceId');
            $sharedConfig = [
                'service' => 'ec2',
                'region' => $region,
                'version' => '2016-11-15',
                'credentials' =>[
                    'key' => $params['serverusername'],
                    'secret' => $params['serverpassword']
                ]
            ];
            $client = new \Aws\AwsClient($sharedConfig);
            if($client){
                $instancesData = $client->describeInstances([
                    'InstanceIds' => [$instanceId]
                ]);

                $reservations = $instancesData->get('Reservations');
                $instanceData=$reservations[0]['Instances'][0];
                if($instanceData){

                   $allocationId= $params["model"]->serviceProperties->get("Allocation Id");
                    if($allocationId){
                        $ip = $client->ReleaseAddress(["AllocationId"=>$allocationId]);
                        if($ip){
                            $params["model"]->serviceProperties->save(["Elastic IP" =>""]);
                            $params["model"]->serviceProperties->save(["Allocation Id" =>""]);
                            Capsule::table("AwsEc2_ElasticIps")->where([
                                'service_id'=>$params['serviceid'],
                                'allocation_id'=>$allocationId
                            ])->delete();
                        }

                    }
                    logModuleCall("elasticip","removeIp",$allocationId,$ip);
                }
                return "success";
            }else{
                logModuleCall(
                    'elasticip',
                    __FUNCTION__,
                    $sharedConfig,
                    "Unable to connect to AWS",
                    ""
                );
                return "Failed";
            }
        }else{
            logModuleCall(
                'elasticip',
                __FUNCTION__,
                $params,
                "Unable to get parent service",
                ""
            );
            return "Failed";
        }
    } catch (Exception $e) {
        logModuleCall(
            'elasticip',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';



    try {
        // https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DisassociateAddress.html DisassociateAddress
        //https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_ReleaseAddress.html ReleaseAddress
        //
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'elasticip',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}



