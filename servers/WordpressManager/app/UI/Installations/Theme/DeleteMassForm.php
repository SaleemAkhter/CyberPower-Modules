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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class DeleteMassForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['deleteMass'];
    }

    public function initContent()
    {
        $this->initIds('themeDeleteMassForm');
        $this->setFormType('deleteMass');
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

        $this->setConfirmMessage('confirmThemeDeleteMass', ['title' => null]);
    }
}
