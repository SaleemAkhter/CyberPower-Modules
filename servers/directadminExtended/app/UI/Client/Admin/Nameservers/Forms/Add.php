<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\VirtualDomain;
class Add extends BaseTabsForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
        ->setProvider(new Providers\Nameservers());

        $this->loadDataToForm();
        $data=$this->dataProvider->getData();
        $domains=[];
        $isVirtual=$data['isnsset'];
        $selecteddomain='';
        if(isset($data['domains']) && !empty($data['domains'])){
            foreach ($data['domains'] as $key => $domain) {
                $domains[$domain->text]=$domain->value;
                if($domain->selected){
                    $selecteddomain=$domain->value;
                }
            }
        }
        $ips=$data['ips'];
        // $provider=$this->->
        if($this->getRequestValue('formData')['virtual'] == 'on'){
            $isVirtual=true;
        }



        if(!$isVirtual){
            $this->addField((new VirtualDomain('domain'))->setDefaultValue($selecteddomain)->setAvailableValues($domains)->addHtmlAttribute('@change', 'initReloadModal()')->setIsVirtual($isVirtual));
            $this->addField((new Fields\Text('ns1'))->setValue($ns1)->setSuffixTranslated(true)->setSuffixText('.'.$selecteddomain))
            ->addField((new Fields\Select('ip1'))->setAvailableValues($ips))
            ->addField((new Fields\Text('ns2'))->setValue($ns2)->setSuffixTranslated(true)->setSuffixText('.'.$selecteddomain))
            ->addField((new Fields\Select('ip2'))->setAvailableValues($ips));
        }else{
            $this->addField((new VirtualDomain('domain'))->setDefaultValue($selecteddomain)->setAvailableValues($domains)->addHtmlAttribute('@change', 'initReloadModal()')->setIsVirtual($isVirtual)->setIsInputDisabled(true));
            $this->addField((new Fields\Text('ns1'))->setValue($ns1)->setSuffixTranslated(true)->setSuffixText('.'.$selecteddomain))
            ->addField((new Fields\Text('ns2'))->setValue($ns2)->setSuffixTranslated(true)->setSuffixText('.'.$selecteddomain))
            ->addField((new Fields\Hidden('virtualon'))->setValue('aoao'));




        }
        $this->loadDataToForm();

    }




    protected function reloadFormStructure()
    {

        if($this->getRequestValue('formData')['virtual'] == 'on')
        {
            unset($this->fields['ip1']);
            unset($this->fields['ip2']);
        }

        $this->dataProvider->reload();
        $this->loadDataToForm();


    }

    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();
        $index = array_search($fieldId, array_keys($array)) + 1;


        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size)
        {
            return -1;
        }
        else
        {
            $temp   = array_slice($array, 0, $index);
            $temp[$newField->getId(). $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }
}
