<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DownloadFile;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;

class FileManager extends AbstractController
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
    \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::FILE_MANAGER) === false) {
            return null;
        }

        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Pages\FileManager::class);
    }

    public function updateCurrentPathNext()
    {
        $oldPath = Request::build()->getSession('fileManagerPath');
        $update  = Request::build()->get('path');
        if ($oldPath) {
            $newPath = $oldPath . '/' . $update;
        } else {
            $newPath = $update;
        }

        Request::build()->addSession('fileManagerPath', $newPath);
    }
    public function getDownloadLink()
    {
        $oldPath = Request::build()->getSession('fileManagerPath');
        $update  = Request::build()->get('path');
        if ($oldPath) {
            $newPath = $oldPath . '/' . $update;
        } else {
            $newPath = $update;
        }

        $downloadHelper = new DownloadFile(Helper\sl('request'));
        $downloadHelper->setPath($newPath)->download();
    }

    public function updateCurrentPathBack()
    {
        $oldPath  = Request::build()->getSession('fileManagerPath');
        $exploded = explode('/', $oldPath);
        $lastKey  = array_search(end($exploded), $exploded);
        unset($exploded[$lastKey]);
        $newPath  = implode('/', $exploded);

        Request::build()->addSession('fileManagerPath', $newPath);
    }

    public function updateCurrentPathBackTo()
    {
        $oldPath  = Request::build()->getSession('fileManagerPath');
        $exploded = explode('/', $oldPath);

        $explodedFlip = array_flip($exploded);
        $explodeReverse = array_reverse($exploded);
        $searchPath = $this->request->get('params')[0];

        foreach ($explodeReverse as $path) {
            if ($path == $searchPath) {
                break;
            }

            unset($explodedFlip[$path]);
        }
        $exploded =  array_flip($explodedFlip);
        if (empty($exploded) || empty($searchPath)) {
            $this->updateToHomeDir();
            return;
        }
        $newPath  = implode('/', $exploded);

        Request::build()->addSession('fileManagerPath', $newPath);
    }

    public function updateToHomeDir()
    {
        Request::build()->reamoveSession('fileManagerPath');
    }
}
