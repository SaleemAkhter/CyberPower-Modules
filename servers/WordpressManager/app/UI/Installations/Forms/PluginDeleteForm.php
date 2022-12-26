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
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PluginProvider;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class PluginDeleteForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('pluginDeleteForm');
        $this->setFormType('delete');
        $this->setProvider(new PluginProvider);
        $this->initFields();
    }

    protected function initFields()
    {

        $field = new Fields\Hidden();
        $field->setName('name');
        $field->setId('name');
        $this->addField($field);
        $field = new Fields\Hidden();
        $field->setName('title');
        $field->setId('title');
        $this->addField($field);
        $this->dataProvider->read();
        $this->setConfirmMessage('confirmPluginDelete', ['name' => $this->dataProvider->getValueById('title')]);
        $this->loadDataToForm();
        
    }
}
