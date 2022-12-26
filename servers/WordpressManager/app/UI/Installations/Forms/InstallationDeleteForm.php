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
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationDeleteForm extends BaseForm implements ClientArea
{
    public function getAllowedActions()
    {
        return ['deleteAndRedirect'];
    }

        public function initContent()
    {
        $this->initIds('deleteForm');
        $this->setFormType('deleteAndRedirect');                
        $this->setProvider(new InstallationProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $this->setConfirmMessage('confirmDelete', ['title' => null]);
        //Delete Directory
        $this->addField(new Fields\Switcher('directoryDelete'));
        //Delete Database with User
        $this->addField(new Fields\Switcher('databaseDelete'));
        
        $this->addField(new Hidden('wpid'));
    }
}
