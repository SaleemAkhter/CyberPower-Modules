<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\TokensTableField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Providers\HttpdConfFile;


class Tokens extends BaseTabsForm implements ClientArea
{
    protected $id    = 'tokensForm';
    protected $name  = 'tokensForm';
    protected $title = 'tokensForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)->setProvider(new HttpdConfFile());
        $this->loadDataToForm();
        $data=$this->dataProvider->getData();
        $tokens=$data['result']->AVAILABLE_TOKENS;
// $tokens=[["A"=>'B']];
        $this->addField((new TokensTableField())->setTokens($tokens));
    }



    protected function reloadFormStructure()
    {

    }

    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();
        $index = array_search($fieldId, array_keys($array)) + 1;


        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size)
        {
            return -1;
        }else{
            $temp   = array_slice($array, 0, $index);
            $temp[$newField->getId(). $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }
}
