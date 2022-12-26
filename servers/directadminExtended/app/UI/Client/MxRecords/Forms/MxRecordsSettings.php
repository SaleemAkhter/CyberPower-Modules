<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Providers\MxRecordsSettingsProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\MxTemplateList;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class MxRecordsSettings extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'mxRecordsSettingsForm';
    protected $name  = 'mxRecordsSettingsForm';
    protected $title = 'mxRecordsSettingsForm';
    protected $class=['shadow1','p-20' ,'mxRecordsSettingsFormContainer'];

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new MxRecordsSettingsProvider());


        $usethisserver = (new Switcher('usethisserver'))->replaceClasses(['lu-form-check', 'lu-col-md-8', 'lu-m-b-2x','usethisserver']);


        $type = (new MxTemplateList('mxtemplate'))->replaceClasses(['lu-form-group', 'lu-col-md-6','ml-10']);

        $this->addField($usethisserver)
        ->addField($type);
        // ->addField($affectpointers);

        $this->loadDataToForm();

        $data=$this->dataProvider->getData();
        foreach ($this->fields as &$field)
        {
            if($field->getId()=="mxtemplate"){
                $field->setAffectpointersValue($data['affectpointers']);
            }

        }

    }
    // protected function reloadFormStructure()
    // {
    //     $this->dataProvider->reload();
    //     $this->loadDataToForm();

    //     $type = $this->getRequestValue('formData')['typeAdd'];
    //     if ($type === 'txt')
    //     {
    //         $this->removeField('value');
    //         $txtValue = new Textarea('txtValue');

    //         $this->addField($txtValue);
    //         $this->dataProvider->reload();
    //         $this->loadDataToForm();
    //     }
    //     elseif($type === 'mx')
    //     {
    //         $this->removeField('value');

    //         $mxNumeric = new Number();
    //         $mxNumeric->initIds('mxNumeric');
    //         $mxNumeric->setDescription('mxNumericDescription');
    //         $mxValue = new Text('mxValue');
    //         $mxValue->setDescription('mxValueDescription');
    //         $this->addField($mxNumeric)
    //             ->addField($mxValue);
    //     }

    // }
}
