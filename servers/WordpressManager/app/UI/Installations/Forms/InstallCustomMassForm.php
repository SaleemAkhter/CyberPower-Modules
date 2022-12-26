<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PluginProvider;

class InstallCustomMassForm extends BaseForm implements ClientArea
{
    protected function getDefaultActions()
    {
        return ['installMass'];
    }

    public function initContent()
    {
        $this->initIds('customPluginInstallMassForm');
        $this->setFormType('installMass');
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
        $this->setConfirmMessage('confirmCustomPluginInstallMass');
    }
}