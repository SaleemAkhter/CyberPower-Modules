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

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationUpdateForm extends BaseStandaloneForm implements ClientArea
{
    use main\App\Http\Client\BaseClientController;
    use main\Core\UI\Traits\Buttons;
    protected function getDefaultActions()
    {
        return ['update'];
    }

    public function initContent()
    {
        $this->initIds('installationUpdateForm');
        $this->setFormType('update');
        $this->setProvider( new InstallationProvider());
        $this->initFields();
    }

    protected function initFields()
    {   
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $wpid = $request->get('wpid') ?? $request->request->get('formData')['wpid'];
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $wpid)
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = main\App\Helper\Wp::subModule($installation->hosting);
        //eu_auto_upgrade 
        $this->addField((new Fields\Select('eu_auto_upgrade'))->setDescription('description'));
        //auto_upgrade_plugins
        $this->addField((new Fields\Switcher('auto_upgrade_plugins'))->setDescription('description'));
        //auto_upgrade_themes 
        $this->addField((new Fields\Switcher('auto_upgrade_themes'))->setDescription('description'));
        $this->addField(new Hidden('wpid'));
        $this->loadDataToForm();
    }
}
