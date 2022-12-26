<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PluginProvider;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @property PluginProvider $dataProvider
 */
class PluginInfoForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['install'];
    }

    public function initContent()
    {
        $this->initIds('PluginInfoForm');
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
        $this->addField((new Fields\Hidden("slug")));
        $this->dataProvider->read();
        $details=['short_description'=> null];
        if($this->dataProvider->getValueById('slug')){
            $details = $this->dataProvider->detail($this->dataProvider->getValueById('slug'));
        }
        $this->setConfirmMessage($details['short_description']);
        $this->loadDataToForm();
       
    }
}
