<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class SshKeyEdit extends SshKey
{

    public function read()
    {

        $data= json_decode(base64_decode($this->actionElementId));
        $this->data['fingerprint']=$data->fingerprint;
        $this->data['comment']=$data->comment;
        $this->data['type']=$data->type;
        $this->data['size']=$data->size;
        $this->loadAdminApi([],false);
        $details=$this->adminApi->sshKey->getKeyDetail($data->fingerprint);
        // debug($details);die();
        $this->data['comment']=$data->comment;
        $this->data['type']=$data->type;
        $this->data['size']=$data->size;
        $this->data['users']=$details->users;
        $this->data['options']=$details->authorized_keys->{$this->data['fingerprint']}->options;
        $this->data['global_keys']=$details->global_keys;


    }
    public function update()
    {
            $options=[
                'environment'=>'environment',
                'from'=>'from',
                'nox11forwarding'=>'no-X11-forwarding',
                'noagentforwarding'=>'no-agent-forwarding',
                'noportforwarding'=>'no-port-forwarding',
                'nopty'=>'no-pty',
                'permitopen'=>'permitopen',
                'tunnel'=>'tunnel',
            ];
            $actionElementId = $this->getRequestValue('actionElementId', '');

            $actiondata= json_decode(base64_decode($actionElementId));
            $fingerprint=$actiondata->fingerprint;
            $data = [
                'fingerprint'      => $fingerprint,
                'comment'=>$this->formData['comment'],
                'who'=>$this->formData['applyto'],
                'global_key'=>($this->formData['globalkey']=="on")?"yes":"",
                'json'         => "yes",
                'action'=>'modify',
            ];
            if($this->formData['selectedFields']){
                $seletedfields=explode(",",$this->formData['selectedFields']);

                foreach ($seletedfields as $key => $field) {
                    if($field){
                        if(isset($options[$field])){
                            $data[$options[$field]]=($this->formData[$field]=="on")?"yes":$this->formData[$field];
                        }else{
                            $data[$field]=$this->formData[$field];
                        }

                    }
                }
            }
            $selectedusers=explode(",",$this->formData['selectedGlobalUsers']);
            foreach ($selectedusers as $key => $user) {
                $data["select".$key]=$user;
            }
            $this->loadAdminApi();
            $response=$this->adminApi->sshKey->update($data);
            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyHasBeenModified');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyModificationFailed');
            }
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyModificationFailed');
    }
}
