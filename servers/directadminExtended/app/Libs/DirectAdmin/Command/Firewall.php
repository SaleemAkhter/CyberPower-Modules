<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Firewall extends AbstractCommand
{
    const CMD_PLUGINS_ADMIN  = 'CMD_PLUGINS_ADMIN';
    const CMD_SERVICE        = 'CMD_SERVICE';


    public function action($data)
    {

        $response = $this->curl->login()->cookierequest(self::CMD_PLUGINS_ADMIN."/csf/index.raw", $data, [
                    ]);
        return $response;
    }

    public function getSettings()
    {
        $apiresult=[
            'status'=>'',
            'isDisabled'=>false,
            'isRunning'=>false,
            'statusClass'=>'bs-callout-success'
        ];

        $response = $this->curl->login()->cookierequest(self::CMD_PLUGINS_ADMIN."/csf/index.raw", [], [
                    ]);
        $dom = new \DOMDocument();
        $dom->loadHTML($response);
        $xpath     = new \DOMXPath($dom);
        $classname='bs-callout';
        $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        if ($nodes->length > 0) {
             $status = $nodes->item(0)->nodeValue;
             $apiresult['status']=$status;
             if(stripos($status, 'Disabled')!==false){
                $apiresult['isDisabled']=true;
                $apiresult['statusClass']='bs-callout-danger';
             }
             if(stripos($status, 'Stopped')!==false){
                $apiresult['isRunning']=true;
             }

        }

        return $apiresult;
    }




}
