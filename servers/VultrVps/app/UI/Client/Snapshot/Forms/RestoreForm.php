<?php
/* * ********************************************************************
*  VultrVps Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Providers\SnapshotProvider;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Hidden;


class RestoreForm extends BaseForm implements ClientArea
{
    public function initContent()
    {
        $this->initIds('restoreForm');
        $this->setFormType('restore');
        $this->setProvider(new SnapshotProvider());
        $this->initFields();
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['restore'];
    }

    private function initFields()
    {
        $this->setConfirmMessage('confirmRestore');
        $this->addField(new Hidden("id"));
    }
}