<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class MessageSystem extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $messages=$this->resellerApi->messageSystem->listdetail();

                $this->data['clear_messages']=$messages->clear_messages;

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }


    public function clearMessages($data)
    {

    }
    public function delete()
    {
        parent::delete();
        $formdata =$this->formData;
        $data        = [
            'subject_select'      => $formdata['subjectcontains'],
            'subject'    => $formdata['subject'],
            'when'=>$formdata['when'],
        ];
        if(isset($formdata['delete_messages_days'])){
            $data['delete_messages_days']=$formdata['delete_messages_days'];
        }
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $response=$this->resellerApi->messageSystem->clearMessages($data);
            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemCleared')->addRefreshTargetId("messageSystem");
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemNotCleared');
            }
        }else{

        }
    }



    public function deleteMany()
    {
        parent::delete();
        $data        = [
            "json"=>"yes",
            "action"=>"multiple",
            "delete"=>"yes",
            "type"=>"message"

        ];
        $messageIds = $this->getRequestValue('massActions', []);

        foreach ($messageIds as $key=>$messageId)
        {
            $data['select'.$key] = $messageId;
        }

        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi([],false);
            $response=$this->resellerApi->messageSystem->deleteMany($data);
            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemDeleted');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemNotDeleted');
            }
        }else{

        }

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mailingListDeleted');
    }
    public function markReadMany()
    {
        parent::delete();

        $data        = [
            'action'=>'multiple',
            'read'=>'Mark as read',
            'sorting'=> 'sort1=-1'
        ];
        $messageIds= $this->getRequestValue('massActions', []);

        foreach ($messageIds as $k=>$messageId)
        {
            $data['select'.$k] = $messageId;

        }
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi([],false);
            $response=$this->resellerApi->messageSystem->markReadMany($data);
            if($response['error']==0){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemMarkedRead');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemNotMarkedRead');
            }
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('messageSystemDeleted');

    }




}
