<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model\Directory;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ProtectedDirectories extends ProviderApi
{
    public function read()
    {   
        $this->loadUserApi();
        $this->loadRequestObj();
        $protectedDirs = $this->userApi->fileManager->protectedDirs($this->getRequestValue('domain'));
        $id = $this->actionElementId;
        $path = $protectedDirs[$id];

        $this->data['path'] = $path;
        $this->data['name'] =  ($this->userApi->fileManager->getName(new Directory(['path' => $path])))->NAME;

    }

    public function delete()
    {
        parent::fileManager();
        $this->loadUserApi();

        $data = [
            'path'     => $this->formData['path'],
            'name'     => $this->formData['name'],
        ];

        $this->userApi->fileManager->unprotect(new Directory($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('protectedDirectories')
            ->setMessageAndTranslate('confirmPdDelete');
    }
}
