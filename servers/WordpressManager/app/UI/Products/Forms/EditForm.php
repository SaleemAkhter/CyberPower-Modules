<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Nov 17, 2017)
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

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\WordpressManager\App\UI\Products\Providers\ProductSettingProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
/**
 * Description of ToggleForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @deprecated since version 1.2.0
 */
class EditForm extends BaseForm implements AdminArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider( new ProductSettingProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $field = new Hidden();
        $field->setName('product_id');
        $field->setId('product_id');
        $this->addField($field);
        $field = new Switcher('debugMode');
        $field->setDescription('Logs on Module Log');
        $this->addField($field);
        try{
            $this->dataProvider->setInstallationScripts($this->dataProvider->getModule()->getInstallationScripts());
            $field = new Select('installationScripts');
            $field->setDescription('description');
            $field->enableMultiple();
            $this->addField($field);
        } catch (\Exception $ex) {
            if(preg_match('/scripts\snot\sfound/', $ex->getMessage())){
                $this->setInternalAlertMessage($ex->getMessage())->setInternalAlertMessageType("danger");
            }
        }
        $this->loadDataToForm();
    }

}
