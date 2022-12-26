<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class File extends ProviderApi
{

    public function read()
    {
        $actiondata= json_decode(base64_decode($this->actionElementId));
        $this->data['file']=$actiondata->file;
        if ($this->data['file']) {
            if ($this->getWhmcsParamByKey('producttype')  == "server") {
                $this->loadAdminApi();
                // if($actiondata->exists) {

                    $this->data = $this->adminApi->fileEditor->get($this->data['file']);
                // } else {
                //     $this->data['error'] = '';
                //     $this->data['FILEDATA']='';
                // }

            } else {
                $this->loadUserApi();
                $result = [];
            }
        } else {
            if ($this->getWhmcsParamByKey('producttype')  == "server") {
                $this->loadAdminApi();
                $this->data = $this->adminApi->fileEditor->get('files');
            } else {
                $this->loadUserApi();
                $result = [];
            }
        }
    }
     public function create()
    {
        parent::create();
        $formData = $this->formData;
// {"rootpass":"Y$4aFwRDpjf3PkKooo","file":"/etc/proftpd.conf","text":"#\n# /etc/proftpd/proftpd.conf -- This is a basic ProFTPD configuration file.\n# To really apply changes, reload proftpd after modifications, if\n# it runs in daemon mode. It is not required in inetd/xinetd mode.\n#\n\n# Includes DSO modules\nInclude /etc/proftpd/modules.conf","json":"yes","action":"save","authenticate":"yes"}:
// $formData['filecontent']="vsftpd : 192.168.2.*";
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
        parent::update();
        $formData = $this->formData;
// {"file":"/etc/hosts.allow","text":"#\n# hosts.allow   This file contains access rules which are used to\n#       allow or deny connections to network services that\n#       either use the tcp_wrappers library or that have been\n#       started through a tcp_wrappers-enabled xinetd.\n###\n#       See 'man 5 hosts_options' and 'man 5 hosts_access'\n#       for information on rule syntax.\n#       See 'man tcpd' for information on tcp_wrappers\n#","json":"yes","action":"save"}:

        $data = [
            "file" => $formData['file'],
            "text" => html_entity_decode($formData['text']),
            "json" => "yes",
            "action" => "save"
        ];

        $this->loadAdminApi();
        $response = $this->adminApi->fileEditor->save($data);
        if (isset($response->success)) {
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        } else {
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('FileAdditionFailed');
        }
    }
}
