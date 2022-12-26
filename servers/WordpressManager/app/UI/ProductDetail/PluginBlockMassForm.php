<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 26, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
/**
 * Description of PluginBlockMassForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockMassForm extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['createMass'];
    }

    public function initContent()
    {
        $this->initIds('PluginBlockMassForm');
        $this->setFormType('createMass');
        $this->setProvider( new PluginBlockedProvider);
        $this->initFields();
    }

    protected function initFields()
    {

        $this->setConfirmMessage('confirmPluginBlockMass', ['title' => null]);
    }
}
