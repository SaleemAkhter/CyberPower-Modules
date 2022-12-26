<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\BackgroundProcess;
class CustomBuild extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $this->data=$this->adminApi->customBuild->getUpdates();

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }
    public function create()
    {
        global $whmcs;
        parent::create();
        $this->loadAdminApi();

        $response=$this->adminApi->customBuild->killprocess($app);

            $whmcsAppConfig = $whmcs->getApplicationConfig();
            $templates_compiledir = $whmcsAppConfig["templates_compiledir"];
            $buldlogs = $whmcsAppConfig["downloads_dir"].'/buildlogs/';
            $files = glob($buldlogs.'/PID_*');

            if(count($files)){
                $lastprocess=explode(".",file_get_contents($files[0]));
                if(count($lastprocess)==2){
                    $timestamp=$lastprocess['0'];
                    $localprocessid=$lastprocess[1];
                    $oldprocess=true;
                    $filePath = $buldlogs. $timestamp  . '.log';
                    $logDataFile = $buldlogs.'PID_'.$localprocessid.'.log';
                    @unlink($logDataFile);
                    @unlink($filePath);
                }
            }

            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('killedSuccessfully');

    }
    public function update()
    {
        parent::update();

        $formData=$this->formData;
        global $whmcs;
        $whmcsAppConfig = $whmcs->getApplicationConfig();
        $templates_compiledir = $whmcsAppConfig["templates_compiledir"];
        $buldlogs = $whmcsAppConfig["downloads_dir"].'/buildlogs/';
        if(!is_dir($buldlogs)){
            mkdir($buldlogs);
        }
        $this->loadAdminApi();

        $app=$_GET['software'];
        $data=$this->adminApi->customBuild->getUpdates('build');
        $exists=false;

        $optionsactivefields=[];
        if(isset($data->data)){
            unset($data->data->build_all);
            unset($data->data->build_cb);
            unset($data->data->build_comp_conf);
            unset($data->data->build_experienced);
            unset($data->data->build_old);
            unset($data->data->build_update);
            unset($data->data->build_update_pcg);
            unset($data->data->build_php_extensions);
        }
        $builds=[];
        foreach ($data->data as $key => $d) {
            $builddata=$d;
            $description=$builddata->description;
            unset($builddata->description);
            unset($builddata->skip);
            foreach ($builddata as $soft => $opt) {
                if($soft==$app){
                    $exists=true;
                    break;
                }
            }

        }



        if(!$exists || $app=='all'){
            echo "Invalid command";
            exit;
        }


        $url=$this->adminApi->customBuild->getApiUrl($app);
        $oldprocess=false;

        $files = glob($buldlogs.'/PID_*');

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-transform, no-cache');
        header("HTTP/1.1 200 OK");
        parse_str(getenv('QUERY_STRING'), $query_string);

        if(count($files)){
            $lastprocess=explode(".",file_get_contents($files[0]));
            if(count($lastprocess)==2){
                $timestamp=$lastprocess['0'];
                $localprocessid=$lastprocess[1];
                $oldprocess=true;
                $filePath = $buldlogs. $timestamp  . '.log';
                $logDataFile = $buldlogs.'PID_'.$localprocessid.'.log';
                $process = new BackgroundProcess('curl -s '.$url );

                $process->setPid($localprocessid);
                $process->setTimestamp($timestamp);
            }
        }
        if(!$oldprocess){
            $timestamp = time();
            $filePath = $buldlogs. $timestamp  . '.log';

            $process = new BackgroundProcess('curl -s '.$url );
            $process->run($filePath,false,$timestamp);
            $logDataFile = $buldlogs.'PID_'.$process->getPid().'.log';

            file_put_contents($logDataFile, $timestamp.'.'.$process->getPid());
        }


        $bytes = 0;
        $data = '';

        $this->sendEvent([
            'data' => $data,
            'pid' => $process->getPid(),
            'finished' => false,
        ]);

        do {
            $data = file_get_contents($filePath);
            if ($bytes !== strlen($data)) {
                $filedataparts=explode("data: ",$data);
                $response=end($filedataparts);
                $responsedata=$data;
                $outputdata=json_decode($response);
                if(json_last_error()==JSON_ERROR_NONE){
                    $responsedata= nl2br($outputdata->data);
                }else{
                    $response=$filedataparts[count($filedataparts)-2];
                    $responsedata=$data;
                    $outputdata=json_decode($response);
                    if(json_last_error()==JSON_ERROR_NONE){
                        $responsedata=nl2br( $outputdata->data);
                    }else{

                        $responsedata="parser error ".$data;
                    }
                }

                $this->sendEvent([
                    'data' => $responsedata,
                    'finished' => false,
                ]);
                $bytes = strlen($data);
            }
            usleep(1000000);
        } while ($process->isRunning());

        $data = file_get_contents($filePath);
        $response=end(explode("data: ",$data));

        $outputdata=json_decode($response);
        if(json_last_error()==JSON_ERROR_NONE){
            $data= nl2br($outputdata->data);
        }

        $this->sendEvent([
            'data' => $data,
            'finished' => true,
        ]);

        @unlink($logDataFile);
        @unlink($filePath);
        exit;

    }
    function sendEvent($msg) {
        echo "data: " . json_encode($msg) . PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
    }
    public function editConfig($data){
        $this->loadAdminApi();
        $response=$this->adminApi->customBuild->updateOptions($data);
        if(isset($response->messages)){
            $responsemessages=[];
            foreach ($response->messages as $key => $row) {
                $responsemessages[]=$row->message;
            }
            $msg=implode("<br>",$responsemessages);
            return (new ResponseTemplates\RawDataJsonResponse())->setMessage($msg);
        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('unabletosavecustombuildoptions');

    }

}
