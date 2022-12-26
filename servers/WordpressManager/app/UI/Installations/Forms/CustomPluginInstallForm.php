<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PluginProvider;

class CustomPluginInstallForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['install'];
    }

    public function initContent()
    {
        $this->initIds('CustomPluginInstallForm');
        $this->setFormType('install');
        
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $this->setProvider( (new PluginProvider)->setInstallation($installation));
        $this->initFields();
    }

    protected function initFields()
    {
        $this->addField((new Fields\Hidden("name")));
        $this->addField((new Fields\Hidden("url")));
        
        $this->setConfirmMessage('confirmPluginInstall',['name'=> null]);
        $this->dataProvider->read();
        $this->loadDataToForm();
       
    }
}
