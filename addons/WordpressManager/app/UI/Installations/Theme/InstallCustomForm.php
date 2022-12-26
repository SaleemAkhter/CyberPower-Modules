<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;


class InstallCustomForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['install'];
    }

    public function initContent()
    {
        $this->initIds('customThemeInstallForm');
        $this->setFormType('install');
        
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $this->setProvider( (new ThemeProvider)->setInstallation($installation));
        $this->initFields();
    }

    protected function initFields()
    {
        $this->addField((new Fields\Hidden("name")));
        $this->addField((new Fields\Hidden("url")));
        $this->setConfirmMessage('confirmCustomThemeInstall');
        $this->localLangReplacements['name'] = null;
        $this->loadDataToForm();
       
    }
}
