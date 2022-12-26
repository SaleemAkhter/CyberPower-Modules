<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;

/**
 * Description of MassDeleteForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class DeleteForm extends BaseForm implements AdminArea
{

    public function getAllowedActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('massDeleteForm');
        $this->setFormType('delete');
        $this->setProvider(new InstanceImageProvider());
        $this->initFields();
    }

    public function initFields()
    {
        $this->addField(new Hidden('id'));
        $this->addField(new Hidden('name'));
        $this->setConfirmMessage('confirmDelete', ['name' => null]);
        $this->loadDataToForm();
    }
}
