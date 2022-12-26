<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms;



use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\Reverse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\Ip;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ReverseForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'reverseForm';
    protected $name  = 'reverseForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Reverse());
        $this->initFields();

        $this->setInternalAlertMessage('ReverseIpInfo');
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $this->addField((new Hidden('id')));

        $this->addField((new Hidden('ipReverse')));
        $this->addField((new Text('ipReverseMock'))->disableField());

        $this->addField((new Text('reverse'))->notEmpty());
    }
}