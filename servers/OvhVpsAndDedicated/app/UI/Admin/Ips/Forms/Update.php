<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips\Geolocation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers\Ip;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Validators\IP as IpValidator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips\Enums as IpEnums;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Validators\Mac;

/**
 * Description of Delete
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Update extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'updateForm';
    protected $name  = 'updateForm';

    public function initContent()
    {
        $this->setInternalAlertMessage('ReverseIpInfo');

        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Ip());

        $this->initFields();
        $this->loadDataToForm();
    }

    public function initFields()
    {
        $field = (new Hidden('ipAddress'))->notEmpty()->addValidator(new IpValidator($this));
        $this->addField($field);

        $field = (new Text('ipAddressMock'))->disableField();
        $this->addField($field);

        $field = (new Text('reverse'));
        $this->addField($field);
    }

}
