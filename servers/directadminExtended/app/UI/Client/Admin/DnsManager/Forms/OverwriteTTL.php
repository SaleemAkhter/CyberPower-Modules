<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Providers\DnsRecords;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;


class OverwriteTTL extends BaseForm implements ClientArea
{
    protected $id    = 'overwriteTTLForm';
    protected $name  = 'overwriteTTLForm';
    protected $title = 'overwriteTTLForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::REORDER)
            ->setProvider(new DnsRecords());

        $actionData = json_decode(base64_decode($this->getRequestValue('actionElementId')));

        $type = (new Select('ttl_select'))->setAvailableValues(['custom'=>'custom','default'=>'default']);
        $value = new Text('value');
        $zone_user=new Hidden('zone_user');
        $this->addField($zone_user)
            ->addField($type)
            ->addField($value);

        $this->loadDataToForm();
    }


}
