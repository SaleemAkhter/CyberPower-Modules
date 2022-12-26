<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Forms;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Machine;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers\AssignProductsProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;


/**
 * Class AssignProductsForm
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignProductsForm extends BaseForm implements AdminArea
{
    use Alerts;

    protected $id    = 'AssignProductsForm';
    protected $name  = 'AssignProductsForm';
    protected $title = 'AssignProductsForm';

    public function initContent()
    {
        $this->setInternalAlertMessage('assignProductsAlertInfo');
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new AssignProductsProvider());

        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField(new Hidden('id'));

        $this->loadRequestObj();
        $serverid = $this->request->get('serverid');

        $products = Machine::getProducts($serverid);

        foreach ($products as $id => $name)
        {
            $this->addField((new Switcher($id))->setName($id)->setRawTitle($name));
        }
    }

}