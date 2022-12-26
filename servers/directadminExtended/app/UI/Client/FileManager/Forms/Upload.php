<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\UploadFile;


//wywalić to i templatki
//use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Upload as UploadField;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class Upload extends BaseForm implements ClientArea
{
    protected $id    = 'uploadForm';
    protected $name  = 'uploadForm';
    protected $title = 'uploadForm';

    public function getDefaultActions()
    {
        return ['upload'];
    }

    public function initContent()
    {
        $this->setFormType('upload')
            ->setProvider(new Providers\FileManager());

        $dirName     = new UploadFile('file');

        $currentPath = Request::build()->getSession('fileManagerPath');
        
        $dirPath = (new Sections\InputGroup('dirPathGroup'))
                ->addInputAddon('path',false, 'home')
                ->addInputComponent((new InputGroupElements\Text('dirPath'))->setDefaultValue($currentPath ? '/' . $currentPath : '/'))
                ->setDescription('description');

        $dirSection = new FormGroupSection('dirSection');
        $dirSection->addField($dirName);

        $this->addSection($dirPath)
             ->addSection($dirSection);
 
    }

}
