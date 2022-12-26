<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Firewall\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Firewall extends ProviderApi
{

    public function read()
    {

        if ($this->getWhmcsParamByKey('producttype')  == "server") {
            $this->loadAdminApi();
            $this->data = $this->adminApi->firewall->getSettings();
        } else {
            $this->loadUserApi();
            $result = [];
        }

    }
     public function create()
    {
        parent::create();
        $formData = $this->formData;

        $data = [
            "file" => $formData['filename'],
            "text" => $formData['filecontent'],
            "rootpass"=>$formData['rootpassword'],
            "json" => "yes",
            "action" => "save",
            "authenticate"=>"yes"
        ];
        // debug($data);die();
        $this->loadAdminApi();
        $response = $this->adminApi->fileEditor->save($data);
        if (isset($response->success)) {
            $data = [
                "file" => $formData['filename'],
                "text" => html_entity_decode($formData['filecontent']),
                "rootpass"=>$formData['rootpassword'],
                "json" => "yes",
                "action" => "save",

            ];
            $response = $this->adminApi->fileEditor->save($data);
            if (isset($response->success)) {
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
            } else {
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('FileAdditionFailed');
            }
        } else {
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('FileSaveFailed');
        }
    }
    public function update()
    {
        global $whmcs;
        parent::update();
        $allowedactions=['qallow','qdeny','qignore','kill','grep','enable','disable','restart','denyf'];
        $formData = $this->formData;
        $action=$whmcs->get_req_var('act');
        if(!in_array($action, $allowedactions)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('actionNotPermitted');
        }
        $data = [
            "ip" => $formData['ip'],
            "action" => $action
        ];

        $this->loadAdminApi();
        $response = $this->adminApi->firewall->action($data);

        $dom = new \DOMDocument();

        $dom->loadHTML($response);
        //Image tag
        $responsetext = $dom->getElementsByTagName("pre");

        if (count($responsetext)>0) {
            $message='';
            foreach ($responsetext as $key => $n) {
                $message.=$n->nodeValue.PHP_EOL;
            }
            return (new ResponseTemplates\RawDataJsonResponse())->setMessage($message);
        } else {
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('FileAdditionFailed');
        }
    }
}
