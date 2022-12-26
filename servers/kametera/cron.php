
<?php
/**
 * 
 * Name: Kamtera Management Plugin for WHMCS
 * Version: v1.0.0
 * Description: This plugin connects WHMCS with Kametera, a cloud service provider, and automates all processes.
 * Developed by Arslan ud Din Shafiq
 * Websoft IT Development Solutions (Private) Limited, Pakistan.
 * WhatsApp: +923041280395
 * WeChat: +923041280395
 * Email: itsaareez1@gmail.com
 * Skype: arslanuddin200911
 * 
 */


/*
 * modified to send service welcome email
 */

require_once __DIR__ . '/../../../init.php';
require_once ROOTDIR.'/modules/servers/kametera/kametera.php';

use WHMCS\Database\Capsule;

$conn = file_get_contents(dirname(__FILE__) .'/storage/connection'); 
$server = json_decode($conn);

$clientId = $server->clientId;
$secret = $server->secret;


if(empty($_REQUEST['test']))
{
    exit;
   //exit('under development');
}

 //logActivity("***---kametera cron initiated---***");

kametera_sync_tables();
kametera_sync_commands($clientId, $secret);
kametera_deletePostCreatedSnapshot($clientId, $secret);


/*
 * check welcome email status and send
 */

 try
 {
     $services = kametera_getServiceIds();

    if(!empty($services))
    {
        foreach ($services as $service) 
        {
            /*
             * check for ip assign
             */
            $ips = Capsule::table('tblhosting')->where('id',$service)->value('assignedips');

            if(!empty($ips))
            {
                /*
                * send email
                */
                $mailStatus = kametera_sendWelcomeEmail($service);

                if($mailStatus['result'] == 'success')
                {
                    logActivity("***---kametera cron: Welcome email sent. ---***");

                    /*
                     * delete email entry
                     */
                    kametera_deleteEmailEntry($service);
                }
                else
                {
                    logActivity("***---kametera cron: Welcome email sent failed. ---***");
                }
            }
            else
            {
                /*
                 * fetch ips
                 */

                $server = new WHMCS\Module\Server();

                if(!$server->loadByServiceID($service)) 
                {
                    logActivity("kametera cron: Welcome email sent failed'" . $server->getServiceModule() . "' Missing");
                }

                $params =  $server->buildParams();

                $subid = kametera_subscriptionIDVerification($params["serviceid"]);

                if ($subid != 0)
                {
                    $subscription_id = kametera_fetchServerID($params['serverusername'], $params['serverpassword'], $params["serviceid"]); 
                    if ($subscription_id != 0)
                    {
                        kametera_updateSubscriptionID($params["serviceid"], $subscription_id);
                        kametera_assignIPCron($params['serverusername'], $params['serverpassword'], $params['serviceid'], $subscription_id);
                    }
                }
                else
                {
                    $subscription_id = kametera_fetchServerID($params['serverusername'], $params['serverpassword'], $params['serviceid']); 
                    if ($subscription_id != 0)
                    {
                        kametera_assignIPCron($params['serverusername'], $params['serverpassword'], $params['serviceid'], $subscription_id);                
                    }
                }

                /*
                 * send welcome email
                 */
                /*
                $mailStatus = kametera_sendWelcomeEmail($service);

                if($mailStatus['result'] == 'success')
                {
                    logActivity("***---kametera cron: Welcome email sent. ---***");



                    kametera_deleteEmailEntry($service);
                }
                else
                {
                    logActivity("***---kametera cron: Welcome email sent failed. ---***");
                }
                */

            }

        }
    }
 }
 catch (Exception $e)
 {
     logActivity("***---kametera cron: Error- {$e->getMessage()} ---***");
 }

logActivity("***---kametera cron terminated---***");

function kametera_sync_tables()
{
    $data = Capsule::table('tblkamtasksqueue')
    ->join('tblkamcommands', 'tblkamtasksqueue.id', '=' , 'tblkamcommands.queueid')
    ->where('tblkamtasksqueue.status', 'pending')
    ->where('tblkamcommands.status', 'success')->get();

    
    if (count($data) > 0)
    {
        foreach ($data as $record)
        {
            Capsule::table('tblkamtasksqueue')->where('id' , $record->queueid)->update(['status' => 'complete']);
        }
    }
}

function kametera_sync_commands($clientId, $secret)
{

    $data = Capsule::table('tblkamcommands')
            ->where('status', 'pendingx')
            ->get();
    
    if (count($data) > 0)
    {
        foreach ($data as $task)
        {
            $status = kametera_getCommandStatus($clientId, $secret, $task->command_id);
            
            if ($status == "complete")
            {
                try 
                {
                    // if (strpos($task->description, "-ss") !== false)
                    // {
                    //     $clientId = explode("-",$task->description);
                    //     $command = 'AddBillableItem';
                    //     $postData = array(
                    //         'clientid' =>  $clientId[0],
                    //         'description' => 'Snapshot ' . kametera_alreadyCreatedSnapshots($clientId, $secret, $task->service_id) + 1,
                    //         'unit' => 'hours',
                    //         'amount' => '0.10',
                    //         'invoiceaction' => 'nextinvoice'
                    //     );
                    //     $res = localAPI($command, $postData);

                    //     Capsule::table('tblkamcommands')
                    //     ->where('id', $task->id)
                    //     ->update(
                    //         [
                    //             'description' => $clientId[1] . "-snapshot",
                    //             'status' => 'success',
                    //         ]
                    //     );                
                    // }
                    // else
                    // {
                        Capsule::table('tblkamcommands')
                        ->where('id', $task->id)
                        ->update(
                            [   
                                'status' => 'success',
                            ]
                        );   

                        if ($task->queueid != null)
                        {
                            Capsule::table('tblkamtasksqueue')
                            ->where('id', $task->queueid)
                            ->update(
                                [   
                                    'status' => 'complete',
                                ]
                            );       
                        }
                          
                        
                    //}
                } 
                catch (\Exception $e) 
                {
                    logModuleCall(
                        'kametera',
                        __FUNCTION__,
                        $vars,
                        $e->getMessage(),
                        $e->getTraceAsString()
                    );
                }
            }
        }
    }
    
    $records = Capsule::table('tblkamtasksqueue')
    ->where('status', 'pending')->get()->unique('server_id');

    if (count($records) > 0)
    {
        foreach ($records as $task){
            if ($task->operation == "cpu")
            {
                $commandId = kametera_changeCPU($clientId, $secret, $task->server_id, $task->requested_value);
            }
            else if ($task->operation == "ram")
            {
                $commandId = kametera_changeRAM($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value));
            }
            else if ($task->operation == "disk0")
            {
                kametera_resizeHardDisk($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value), 0, 1);
            }
            else if ($task->operation == "disk1-delete")
            {
                 $commandId = kametera_removeDisk($clientId, $secret, $task->server_id, 1);
            }
            else if ($task->operation == "disk2-delete")
            {
                $commandId = kametera_removeDisk($clientId, $secret, $task->server_id, 2);
            }
            else if ($task->operation == "disk1-create")
            {
                $commandId = kametera_addNewDisk($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value));
            }
            else if ($task->operation == "disk2-create")
            {
                $commandId = kametera_addNewDisk($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value));
            }
            else if ($task->operation == "disk1")
            {
                $commandId = kametera_resizeHardDisk($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value), 1, 1);
            }
            else if ($task->operation == "disk2")
            {
                $commandId = kametera_resizeHardDisk($clientId, $secret, $task->server_id, preg_replace("/[^0-9]/", "", $task->requested_value), 2, 1);
            }
            else if ($task->operation == "create-ss")
            {
                // $number_of_existing_ss_on_kameterea = kametera_alreadyCreatedSnapshots($clientId, $secret, $task->serverId);
                // $allowed_snapshots = explode("-",$task->requested_value)[2];
                // logModuleCall(
                //     'kametera',
                //     __FUNCTION__,
                //     "$number_of_existing_ss_on_kameterea: " . $number_of_existing_ss_on_kameterea,
                //     "$allowed_snapshots: " . $allowed_snapshots,
                //     $e->getTraceAsString()
                // );
                //  if ($number_of_existing_ss_on_kameterea >= 0 && $number_of_existing_ss_on_kameterea < 5 && $allowed_snapshots > $number_of_existing_ss_on_kameterea)
                // {
                //     $commandId = kametera_createSnapshot($clientId, $secret, $task->serverId, "");
                // }
            }
            else if ($task->operation == "delete-ss")
            {
                
            }
            $pdo = Capsule::connection()->getPdo();
            $pdo->beginTransaction();
            $status = "";
            if ($commandId["message"] == "success")
            {
                $status = "pendingx";
            }
            else
            {
                $status = $commandId["message"];
            }
            try {

                    $statement = $pdo->prepare(
                        'insert into tblkamcommands (service_id, command_id , status, description, queueid) values (:service_id, :command_id, :status, :description, :queueid)'
                    );
                
                    $statement->execute(
                        [
                            ':service_id' => $task->server_id,
                            ':command_id' => $commandId["code"],
                            ':status' => $status,
                            ':description' => $task->operation,
                            ':queueid' => $task->id
                        ]
                    );
                
                    $pdo->commit();    

                return $commandId["message"];
            } catch (\Exception $e) {
                echo "Uh oh! {$e->getMessage()}";
                $pdo->rollBack();
            }
        }
    }
}

function kametera_deletePostCreatedSnapshot($clientId, $secret)
{
    $disk0 = Capsule::table('tblkamcommands')
    ->where('status', 'success')
    ->where('description', 'disk0')->first();

    $disk1 = Capsule::table('tblkamcommands')
    ->where('status', 'success')
    ->where('description', 'disk1')->first();

    $disk2 = Capsule::table('tblkamcommands')
    ->where('status', 'success')
    ->where('description', 'disk2')->first();

    if (count($disk0) > 0)
    {
        kametera_deleteAllSS($clientId, $secret, $disk0->service_id);
        Capsule::table('tblkamcommands')
        ->where('id', $disk0->id)
        ->update(
            [   
                'description' => 'disk0-ok',
            ]
        );
    }
    else if (count($disk1) > 0)
    {
        kametera_deleteAllSS($clientId, $secret, $disk1->service_id);
        Capsule::table('tblkamcommands')
        ->where('id', $disk1->id)
        ->update(
            [   
                'description' => 'disk1-ok',
            ]
        );
    }
    else if (count($disk2) > 0)
    {
        kametera_deleteAllSS($clientId, $secret, $disk2->service_id);
        Capsule::table('tblkamcommands')
        ->where('id', $disk2->id)
        ->update(
            [   
                'description' => 'disk2-ok',    
            ]
        );
    }
}

function kametera_getServiceIds()
{
    return Capsule::table('kametera_welcome_email_stats')->where('emailsent',0)->pluck('serviceid');
}

function kametera_deleteEmailEntry($serviceId)
{
    return Capsule::table('kametera_welcome_email_stats')->where('serviceid',$serviceId)->delete();
}

function kametera_sendWelcomeEmail($serviceid)
{
    /*
     * check email template exist
     * Kamatera Server Details
     */
    
    if(!empty(Capsule::table('tblemailtemplates')->where('name','Kamatera Server Details')->first()))
    {
        $command = 'SendEmail';
        $postData = array(
                        'messagename' => 'Kamatera Server Details',
                        'id' => $serviceid,
                    );
        
        return localAPI($command, $postData);
    }
    else
    {
        logActivity("***--- kametera cron: Email template not found.-Kamatera Server Details ---***");
    }
}


function kametera_assignIPCron($clientId, $secret, $service_id, $serverId)
{   
    try 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://console.kamatera.com/service/server/{$serverId}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("AuthClientId: {$clientId}","AuthSecret: {$secret}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        if ($status == 200)
        {
            $res = json_decode($body);
            
            logModuleCall('kametera',__FUNCTION__,$res->networks[0]->ips[0],"","");
            
            if (isset($res->networks[0]->ips[0]))
            {
                $r = Capsule::table('tblhosting')
                ->where('id', $service_id)
                ->where('subscriptionid', $serverId)
                ->update(
                    [
                        'assignedips' => $res->networks[0]->ips[0],
                    ]
                );
    
                if (count($r) > 0)
                {
                    
                    return true;
                }
                else
                {
                    return false;
                }    
            }
            else
            {
                return "Error: IP is not assigned.";
            }
        }
        else
        {
            $e = json_decode($body);
            return $e->errors[0]->info;
        }

    } catch (Exception $e) {
        logModuleCall(
            'kametera',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return array(
            'success' => false,
            'errorMsg' => $e->getMessage(),
        );
    }
}