<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Providers\DnsRecordsCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class Create extends BaseForm implements ClientArea
{
    protected $id    = 'createRecordForm';
    protected $name  = 'createRecordForm';
    protected $title = 'createRecordForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new DnsRecordsCreate());


        $type = (new Select('typeAdd'))->addHtmlAttribute('bi-event-change', 'initReloadModal');
        $name = new Text('name');
        $value = new Text('value');


        $this->addField($type)
        ->addField($name)
        ->addField($value);

        $this->loadDataToForm();
    }
    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();
        $this->loadDataToForm();

        $type = $this->getRequestValue('formData')['typeAdd'];
        if ($type === 'txt')
        {
            $this->removeField('value');
            $txtValue = new Textarea('txtValue');

            $this->addField($txtValue);
            $this->dataProvider->reload();
            $this->loadDataToForm();
        }
        elseif($type === 'mx')
        {
            $this->removeField('value');

            $mxNumeric = new Number();
            $mxNumeric->initIds('mxNumeric');
            $mxNumeric->setDescription('mxNumericDescription');
            $mxValue = new Text('mxValue');
            $mxValue->setDescription('mxValueDescription');
            $this->addField($mxNumeric)
                ->addField($mxValue);
        }

    }
}
