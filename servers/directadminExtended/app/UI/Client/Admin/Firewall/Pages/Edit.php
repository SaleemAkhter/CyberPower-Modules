<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Firewall\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AriaField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AppBox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Firewall\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormTableSection;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;

class Edit extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;

    protected $id = 'editSshKeyForm';
    protected $name = 'editSshKeyForm';
    protected $title = 'keydata';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-firewall-edit';

    public function initContent()
    {

        $this->addReplacement();
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\Firewall());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
    }
    public function returnAjaxData()
    {

        $data = [];
        $this->loadRequestObj();
        if(isset($_POST['formData'])){
            return $this->dataProvider->update();
        }else{
            $optionsactivefields=[];
            $data=$this->dataProvider->getData();
            $this->data=$data;
        }

        return (new RawDataJsonResponse(['data' => $this->data]));
    }
    protected function getPackageIpFieldsSection()
    {
        $selectFieldsSection =(new FormGroupSection('packagesSection'))
            ->addField((new Select('ip'))->setFormId($this->id)->setContainerClasss(['lu-col-md-8']));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();
        $this->loadDataToForm();
    }



    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();

        $index = array_search($fieldId, array_keys($array)) + 1;

        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size) {
            return -1;
        } else {
            $temp = array_slice($array, 0, $index);
            $temp[$newField->getId() . $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }



}
