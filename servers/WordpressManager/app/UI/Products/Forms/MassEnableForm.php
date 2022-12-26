<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 5, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Products\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\UI\Products\Providers\ProductSettingProvider;

/**
 * Description of MassForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class MassEnableForm extends BaseForm implements AdminArea
{
    protected $id = 'massForm';

    public function getAllowedActions()
    {
        return ['updateMass'];
    }

    public function initContent()
    {
        $this->setFormType('updateMass');
        $this->setProvider(new ProductSettingProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $this->setConfirmMessage('confirmToogle', ['title' => null]);
    }
}
