<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\IFileManager;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Copyable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Deleteable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Permissionable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Compressible;

/**
 * Description of FileManager
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class FileManager
{
    /*
     * @var \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\IFileManager
     */
    private $fileManager;

    public function __construct(IFileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function createDirectory(Directory $model)
    {
        return $this->fileManager->createDirectory($model);
    }

    public function createFile(File $model)
    {
        return $this->fileManager->createFile($model);
    }

    public function copy(Copyable $model)
    {
        return $this->fileManager->copy($model);
    }

    public function move(Copyable $model)
    {
        return $this->fileManager->move($model);
    }

    public function delete(Deleteable $model)
    {
        return $this->fileManager->delete($model);
    }

    public function lists(Directory $model)
    {
        return $this->fileManager->lists($model);
    }

    public function upload(File $model)
    {
        return $this->fileManager->upload($model);
    }

    public function rename(File $model)
    {
        return $this->fileManager->rename($model);
    }

    public function chmod(Permissionable $model)
    {
        return $this->fileManager->chmod($model);
    }

    public function compress(Compressible $model)
    {
        return $this->fileManager->compress($model);
    }

    public function protect(Directory $model)
    {
        return $this->fileManager->protect($model);
    }

    public function extract(Compressible $model)
    {
        return $this->fileManager->extract($model);
    }

    public function getFileContent(File $model)
    {
        return $this->fileManager->getFileContent($model);
    }

    public function saveFileContent(File $model)
    {
        return $this->fileManager->saveFileContent($model);
    }

    public function downloadFile(File $model){
        return $this->fileManager->downloadFile($model);
    }

}
