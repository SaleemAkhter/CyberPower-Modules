<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;

class Plugin extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi([],false);
                // $ns=$this->adminApi->plugin->get();


            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }
    // public function create()
    // {
    //     parent::create();
    //     $formData=$this->formData;
    //     $data = [
    //         'passwd'       => $formData['password'],
    //         'json'         => 'yes',
    //         'action'=>'add',
    //         'install'=>($formData['uploadMethod']=="on")?"yes":"no",
    //         'type'=>$formData['uploadMethod'],
    //         'file1'=>$_FILES['plugin'],
    //     ];

    //     $this->loadAdminApi();
    //     $response=$this->adminApi->pluginManager->action($data);
    //     if(isset($response->success)){
    //         return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->success);
    //     }else{
    //         return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
    //     }
    // }

public function create()
    {
        parent::fileManager();

        // if (!$this->request->files->get('formData')['plugin']) {
        //     return (new ResponseTemplates\RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('youHaveToSelectFile');
        // }

        $formData=$this->formData;
        $path        = $this->request->request->get('formData')['dirPath'] ?: '/';
        $tmpPath     = $this->request->files->get('formData')['plugin']->getPathname();
        $orginalName = $this->request->files->get('formData')['plugin']->getClientOriginalName();
        $type        = $this->request->files->get('formData')['plugin']->getType();


        $file = new \CURLFile($tmpPath, $type, $orginalName);
        $data = [
            'path'    => $path,
            'name'    => $orginalName,
            'content' => $file,

        ];
        $data = [
            'passwd'       => $formData['password'],
            'json'         => 'yes',
            'action'=>'add',
            'install'=>($formData['uploadMethod']=="on")?"yes":"no",
            'type'=>$formData['uploadMethod'],
            'file1'=>$file,
            'enctype'   => "multipart/form-data",
        ];
        $this->loadAdminApi();
        $response=$this->adminApi->pluginManager->action(new File($data),$data);

        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->success);
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        }
    }
    public function delete()
    {
        parent::delete();
        $formData=$this->formData;
        $data = [
            'passwd'       => $formData['password'],
            'json'         => 'yes',
            'action'=>'select',
            'delete'=>'yes',
            'select0'=>$formData['plugin'],
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->pluginManager->action($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->success);
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        }
    }






}
