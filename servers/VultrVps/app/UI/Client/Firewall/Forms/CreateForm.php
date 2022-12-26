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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\VultrVps\App\UI\Validators\FirewallValidator;
use ModulesGarden\Servers\VultrVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Textarea;


class CreateForm extends BaseForm implements ClientArea
{
    public function initContent()
    {
        $this->initIds('createForm');
        $this->setFormType('create');
        $this->setProvider(new FirewallProvider());
        $this->setInternalAlertMessageType(AlertTypesConstants::INFO);
        $this->initFields();
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['create'];
    }

    private function initFields()
    {
        //type
        $field = new Select('type');
        $this->addField($field);
        //protocol
        $field = new Select('protocol');
        $this->addField($field);
        //port
        $field = new Text('port');
        $field->setDescription('description');
        $this->addField($field);
        //source
        $field = new Select('source');
        $this->addField($field);
        //subnet
        $field = new Text('subnet');
        $this->addField($field);
        //subnet_size
        $field = new Text('subnet_size');
        $this->addField($field);
        //notes
        $field = new Textarea('notes');
        $field->addHtmlAttribute('maxlength',255);
        $this->addField($field);
    }
}