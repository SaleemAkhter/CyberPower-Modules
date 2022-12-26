<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class SshKeyPaste extends SshKey
{

    public function read()
    {
        $this->data['key'] = $this->actionElementId;
    }
    public function create()
    {
        // debug($this->formData);
        $sshkey=$this->formData['line'];
         // parent::create();
         // die("ajskj");
         // {"text":"ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDKZTSjpEudhKxqFUFhP5Dc9GgUWyJFQDtiedrOnXmU0dKuf6E5trnThUMR1uuIZFWNWnHb XxxsbE1XQIyQSV//LT 5xCmwGCTgazVPmNLSBYjhftfAvNkIiExC7yKxxtxqmi8FR/KRJ0zZNlH7Id0UdNKENpOilILBxH8DqlZOw: = werwerwerwe","json":"yes","type":"paste","action":"authorize"}
            $data = [
                'text'      => $sshkey,
                'json'         => "yes",
                'action'=>'authorize',
                'type'=>'paste',
            ];

            $this->loadAdminApi();
            $response=$this->adminApi->sshKey->paste($data);

            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyHasBeenCreated');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyCreationFailed');
            }
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyCreationFailed');
    }
}
