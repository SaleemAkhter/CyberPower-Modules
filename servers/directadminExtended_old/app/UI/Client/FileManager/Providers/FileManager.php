<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;

use Exception;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\Permissions;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\FileList;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class FileManager extends ProviderApi
{

    public function read()
    {

        $data = json_decode(base64_decode($this->actionElementId));
        $explodeTruePath = explode('/', $data->truepath);
        $file            = end($explodeTruePath);

        $this->data['oldFile']  = $file;
        $this->data['file']     = $file;
        unset($explodeTruePath[array_search($file, $explodeTruePath)]);
        $this->data['path'] = (!empty(implode('/', $explodeTruePath))) ? implode('/', $explodeTruePath) : '/';
    }

    public function create()
    {
        parent::fileManager();

        $data = [
            'path'  => '/' . Request::build()->getSession('fileManagerPath'),
            'name'  => $this->formData['name']
        ];
        $this->fileManager->createDirectory(new Directory($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('directoryHasBeenCreated')->addRefreshTargetId('fileManagerPage');
    }

    public function rename()
    {
        parent::fileManager();

        $data = [
            'path'     => $this->formData['path'],
            'name'     => $this->formData['oldFile'],
            'newName'  => $this->formData['file']
        ];
        $this->fileManager->rename(new File($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('fileHasBeenRenamed');
    }

    public function delete()
    {
        parent::fileManager();

        $data = [
            'path'      => '/',
            'fullPath'  => $this->formData['path'] . '/' . $this->formData['file']
        ];
        $fileList = (new FileList())->setList([new File($data)]);
        $this->fileManager->delete($fileList);

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('fileHasBeenDeleted');
    }

    public function deleteMany()
    {
        parent::fileManager();

        $fileList = [];
        foreach ($this->getRequestValue('massActions', []) as $truepath) {
            $truepath = json_decode(base64_decode($truepath));
            $explode  = explode('/', $truepath->truepath);
            $fileName = end($explode);
            unset($explode[array_search($fileName, $explode)]);
            $path = implode('/', $explode);
            $data = [
                'path'      => '/',
                'fullPath'  => $path . '/' . $fileName
            ];
            $fileList[] = new File($data);
        }
        $list = (new FileList())->setList($fileList);
        $this->fileManager->delete($list);

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('filesHaveBeenDeleted');
    }

    public function permissions()
    {
        parent::fileManager();

        $data = [
            'permissions' => Permissions::getEncodeInstance()->getCode($this->formData),
            'path'        => '/',
            'fullPath'  => $this->formData['path'] . '/' . $this->formData['file']
        ];

        $fileList = (new FileList())->setList([new File($data)]);

        $this->fileManager->chmod($fileList);

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('filePermissionsChanged');
    }

    public function permissionMany()
    {
        parent::fileManager();

        $fileList = [];
        foreach ($this->getRequestValue('massActions', []) as $truepath) {

            $truepath = json_decode(base64_decode($truepath));

            $explode  = explode('/', $truepath->truepath);
            $fileName = end($explode);
            unset($explode[array_search($fileName, $explode)]);
            $path = implode('/', $explode);
            $data = [
                'permissions' => Permissions::getEncodeInstance()->getCode($this->formData),
                'path'      => '/',
                'fullPath'  => $path . '/' . $fileName
            ];
            $fileList[] = new File($data);
        }
        $list = (new FileList())->setList($fileList);
        $this->fileManager->chmod($list);

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('filesPermissionsHaveBeenUpdated');
    }

    public function copy()
    {
        parent::fileManager();

        $data     = [
            'path'              => $this->formData['path'] . '/' . $this->formData['file'],
            'newPath'           => $this->formData['newPath'] . '/' .  $this->formData['file'],
            'additionalFields'  => [
                'overwrite' => $this->formData['overwrite'] == 'on' ? 'yes' : 'no'
            ]
        ];
        $this->fileManager->copy(new File($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('fileHasBeenCopied');
    }

    public function upload()
    {
        parent::fileManager();

        if (!$this->request->files->get('formData')['file']) {
            return (new ResponseTemplates\RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('youHaveToSelectFile');
        }


        $path        = $this->request->request->get('formData')['dirPath'] ?: '/';
        $tmpPath     = $this->request->files->get('formData')['file']->getPathname();
        $orginalName = $this->request->files->get('formData')['file']->getClientOriginalName();
        $type        = $this->request->files->get('formData')['file']->getType();


        $file = new \CURLFile($tmpPath, $type, $orginalName);
        $data = [
            'path'    => $path,
            'name'    => $orginalName,
            'content' => $file
        ];
        $this->fileManager->upload(new File($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('fileHaveBeenUploaded');
    }

    public function protect()
    {
        parent::fileManager();
       
        $data = [
            'path'     => $this->formData['path'] . '/' . $this->formData['file'],
            'name'     => $this->formData['name'],
            'user'     => $this->formData['user'],
            'password'  => $this->formData['password'],
        ];

        if (!str_contains($data['path'], 'public_html')) {
            return (new ResponseTemplates\RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('haveToBePublicHtml');
        }

        $this->fileManager->protect(new Directory($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('fileManagerPage')
            ->setMessageAndTranslate('passwordHasBeenSet');
    }

}
