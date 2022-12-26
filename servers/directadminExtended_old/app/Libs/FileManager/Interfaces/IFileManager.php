<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\File;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Deleteable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Copyable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Permissionable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Compressible;

/**
 * Description of IFileManager
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
interface IFileManager
{

    public function lists(Directory $model);

    public function createDirectory(Directory $model);

    public function createFile(File $model);

    public function delete(Deleteable $model);

    public function copy(Copyable $model);
    
    public function move(Copyable $model);

    public function upload(File $model);

    public function rename(File $model);
    
    public function chmod(Permissionable $model);
    
    public function compress(Compressible $model);

    public function protect(Directory $model);
    
    public function extract(Compressible $model);
    
    public function getFileContent(File $model);
    
    public function saveFileContent(File $model);

    public function downloadFile(File $model);
}
