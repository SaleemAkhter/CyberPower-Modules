<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Providers;


class Paste extends BaseTabsForm implements ClientArea
{
    protected $id    = 'pasteForm';
    protected $name  = 'pasteForm';
    protected $title = 'pasteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\SshKeyPaste());
        $this->addField((new Fields\Text('line'))->setPlaceholder('(options) ssh-rsa ... comment'));

        $this->loadDataToForm();
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
        }
        else
        {
            $temp   = array_slice($array, 0, $index);
            $temp[$newField->getId(). $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }
}
