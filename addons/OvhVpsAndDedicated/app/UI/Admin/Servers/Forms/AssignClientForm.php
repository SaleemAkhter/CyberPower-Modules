<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Forms;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Fields\ClientRemoteSearch;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Fields\ClientServiceAjax;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers\AssignClientProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Class AssignClientForm
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignClientForm extends BaseForm implements AdminArea
{
    use Alerts;

    protected $id    = 'AssignClientForm';
    protected $name  = 'AssignClientForm';
    protected $title = 'AssignClientForm';

    public function initContent()
    {
        $this->setInternalAlertMessage('assignClientAlertInfo');
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new AssignClientProvider());
        $this->initFields();

        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField(new Hidden('id'));

        $this->addField((new ClientRemoteSearch('clientRemoteSearch'))->notEmpty());

        $this->addField((new ClientServiceAjax('service'))->notEmpty()->addReloadOnChangeField('clientRemoteSearch'));
   }
}
