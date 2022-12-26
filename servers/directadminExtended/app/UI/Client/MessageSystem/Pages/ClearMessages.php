<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Forms\Fields\SelectWithText;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;


class ClearMessages extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'clearMessagesForm';
    protected $name = 'clearMessagesForm';
    protected $title = '';
    protected $formdata=[];
    public function initContent()
    {
        $form = $this->getRequestValue('formData' ,false);
        if(isset($form['when'])){
            $this->setProvider(new Providers\MessageSystem());
            $this->dataProvider->read();
            $data=$this->dataProvider->getData();
            return $this->dataProvider->clearMessages($form);
        }
        $this->addReplacement();
        $this->setRawTitle("Clear Messages System");
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Providers\MessageSystem());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();

        $subjectselects=[];
        $selectedsubject='';
        if(isset($data['clear_messages']) && isset($data['clear_messages']->SUBJECT_SELECT)){
            foreach ($data['clear_messages']->SUBJECT_SELECT as $key => $option) {
                $subjectselects[$option->value]=$option->text;
                if($option->selected){
                    $selectedsubject=$option->value;
                }
            }
        }
        $whenselects=[];
        $whenselected='';
        if(isset($data['clear_messages']) && isset($data['clear_messages']->WHEN_SELECT)){
            foreach ($data['clear_messages']->WHEN_SELECT as $key => $option) {
                $whenselects[$option->value]=$option->text;
                if($option->selected){
                    $whenselected=$option->value;
                }
            }
        }
        $this->formData=[
            'subjectselects'=>$subjectselects,
            'selectedsubject'=>$selectedsubject,
            'whenselects'=>$whenselects,
            'whenselected'=>$whenselected
        ];
        $selectFieldsSection=$this->getClearMessageFieldsSection();
        $this->addSection($formBox)->addSection($selectFieldsSection);

        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId("clearMessagesForm")->setTitle("Delete");
        $submitButton->runInitContentProcess();
        $this->setSubmit($submitButton);
        $this->loadDataToForm();


    }
    public function loadData()
    {
       die("adjlksadljk");
    }
    protected function getClearMessageFieldsSection()
    {

        $selectFieldsSection =(new FormGroupSection('packagesSection'))
            ->addField(
                (new SelectWithText('subjectcontains'))
                ->setAvailableValues($this->formData['subjectselects'])
                ->setSelectedValue($this->formData['selectedsubject'])
                ->setFormId($this->id)
                ->setContainerClasss(['lu-col-md-8'])
                ->setInputName('subject')
            )
            ->addField(
                (new SelectWithText('when'))
                ->setAvailableValues($this->formData['whenselects'])
                ->setSelectedValue($this->formData['whenselected'])
                ->setFormId($this->id)
                ->setContainerClasss(['lu-col-md-8'])
                ->setInputName('delete_messages_days')
                ->setInputLabel('days')
                ->setIsInputDisabled(true)
            );
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }


}
