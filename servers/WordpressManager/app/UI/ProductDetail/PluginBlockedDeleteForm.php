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
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of PluginBlockedDeleteForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockedDeleteForm  extends BaseForm implements AdminArea
{
    protected function getDefaultActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('pluginBlockedDeleteForm');
        $this->setFormType('delete');
        $this->setProvider( new PluginBlockedProvider);
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        $this->setConfirmMessage('confirmPluginBlockedDelete', ['name' => null]);
        sl('lang')->addReplacementConstant('name', $this->dataProvider->getValueById('name'));
        $this->addField((new Hidden('name'))->setValue($this->dataProvider->getValueById('name')));
        $this->addField((new Hidden('id'))->setValue($this->dataProvider->getValueById('id')));
    }
}
