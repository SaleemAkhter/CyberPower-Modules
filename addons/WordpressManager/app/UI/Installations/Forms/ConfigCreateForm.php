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
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\ConfigProvider;
use \ModulesGarden\WordpressManager\App\UI\Installations\Fields\TextValidator;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ConfigCreateForm extends BaseForm implements ClientArea
{

    public function initContent()
    {
        $this->initIds('ConfigCreateForm');
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new ConfigProvider);
        $this->initFields();
    }

    protected function initFields()
    {
        $field = new Fields\Hidden();
        $field->setName('id');
        $field->setId('id');
        $this->addField($field);
        //Name
        $field = new Fields\Text('name');
        $field->setId('name')->addValidator(new TextValidator('/^[A-Za-z0-9\_]*$/'));
        $this->addField($field);
        //Value
        $field = new Fields\Text('value');
        $field->setId('value');
        $this->addField($field);
        //Type
        $field = new Fields\Select('type');
        $field->setId('type');
        $lang =  sl('lang');
        $field->setAvailableValues(["constant" =>  $lang->absoluteT("constant"), "variable" => $lang->absoluteT("variable")]);
        $this->addField($field);
        $this->loadDataToForm();
    }
}
