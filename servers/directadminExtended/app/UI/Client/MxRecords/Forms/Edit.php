<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Providers\DnsRecords;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;


class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new DnsRecords());

        $actionData = json_decode(base64_decode($this->getRequestValue('actionElementId')));

        $type = (new Text('type'))->disableField();
        $name = (new Text('name'));
        $value = new Text('value');
        $ttl = new Hidden('ttl');
        $typeTmp = new Hidden('typeTmp');
        $oldVal = new Hidden('oldValue');
        $oldName = new Hidden('oldName');

        $this
            ->addField($type)
            ->addField($typeTmp)
            ->addField($ttl)
            ->addField($oldVal)
            ->addField($oldName)
            ->addField($name);

        if(isset($actionData->type) && $actionData->type == 'MX')
        {
            $this->addMxValue();
            $this->loadDataToForm();
            return;
        }

        $this->addField($value);
        $this->loadDataToForm();
    }

    public function addMxValue()
    {
        $mxNumeric = new Number();
        $mxNumeric->initIds('mxNumeric');
        $mxNumeric->setDescription('mxNumericDescription');
        $mxValue = new Text('mxValue');
        $mxValue->setDescription('mxValueDescription');
        $this->addField($mxNumeric)
        ->addField($mxValue);
    }
}
