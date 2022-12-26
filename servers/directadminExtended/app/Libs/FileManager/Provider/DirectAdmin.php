<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-08-08
 * Time: 13:58
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Provider;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Download\Download;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Compressible;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Copyable;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Deleteable;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\IFileManager;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Permissionable;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DirectAdmin implements IFileManager
{

    private $provider;
    private $responseBuilder;

    public function __construct($provider)
    {
        $this->provider        = $provider;
        //$this->responseBuilder = new ResponseBuilder();
    }

    public function lists(Directory $model)
    {
        // TODO: Implement lists() method.
    }

    public function createDirectory(Directory $model)
    {
        $data = [
            'path' => $model->getPath(),
            'name' => $model->getName()
        ];
        $this->provider->fileManager->createDirectory(new Models\Command\FileManager($data));
    }

    public function protect(Directory $model)
    {
        $data = [
            'path' => $model->getPath(),
            'name' => $model->getName(),
            'user' => $model->getUser(),
            'password' => $model->getPassword()
        ];

        $this->provider->fileManager->protect(new Models\Command\FileManager($data));
    }

    public function createFile(File $model)
    {
        throw new \Exception('Unsupported action.');
    }

    public function delete(Deleteable $model)
    {
        $modelsArray = [];
        foreach ($model->getList() as $element)
        {
            $data = [
                'path'      => $element->getPath(),
                'truepath'  => $element->getFullPath()
            ];
            $modelsArray[] = new Models\Command\FileManager($data);
        }

        $this->provider->fileManager->delete($modelsArray);
    }

    public function copy(Copyable $model)
    {
        $data = [
            'old'       => $model->getPath(),
            'name'      => $model->getNewPath(),
            'path'      => '/',
            'overwrite' => $model->getAdditionalFields()['overwrite']
        ];
        $this->provider->fileManager->copy(new Models\Command\FileManager($data));
    }

    public function move(Copyable $model)
    {
        // TODO: Implement move() method.
    }

    public function upload(File $model)
    {
        $data = [
            'name'  => $model->getName(),
            'file'  => $model->getContent(),
            'path'  => $model->getPath()
        ];
        $this->provider->fileManager->upload(new Models\Command\FileManager($data));
    }

    public function rename(File $model)
    {
        $data = [
            'path'  => $model->getPath(),
            'old'   => $model->getName(),
            'name'  => $model->getNewName()
        ];
        $this->provider->fileManager->rename(new Models\Command\FileManager($data));
    }

    public function chmod(Permissionable $model)
    {
        $modelsArray = [];
        foreach ($model->getList() as $element)
        {
            $data = [
                'path'      => $element->getPath(),
                'truepath'  => $element->getFullPath(),
                'chmod'     => $element->getPermissions()
            ];
            $modelsArray[] = new Models\Command\FileManager($data);
        }
        $this->provider->fileManager->permission($modelsArray);
    }

    /**
     * @param File $model
     */
    public function downloadFile(File $model)
    {
        $data = [
            'path'  =>$model->getFullPath()
        ];

       $fileContent =  $this->provider->fileManager->downloadFile(new Models\Command\FileManager($data));

       /*
        * Prepare download file
        */
       Download::prepare($model->getName(), $fileContent);
    }

    public function compress(Compressible $model)
    {
        // TODO: Implement compress() method.
    }

    public function extract(Compressible $model)
    {
        // TODO: Implement extract() method.
    }

    public function getFileContent(File $model)
    {
        // TODO: Implement getFileContent() method.
    }

    public function saveFileContent(File $model)
    {
        // TODO: Implement saveFileContent() method.
    }

}