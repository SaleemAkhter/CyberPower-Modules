<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\LoaderField;

class Progress extends BaseTabsForm implements ClientArea
{
    protected $id    = 'progressForm';
    protected $name  = 'progressForm';
    protected $title = 'progressForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\CustomBuild());
            $this->addField(new LoaderField('loadingCustomBuildOutput'));
            // LoaderField
        // $this->loadDataToForm();
    }



    protected function reloadFormStructure()
    {
        $bandwidthValue = $this->getRequestValue('formData')['bandwidth'];
        if ($bandwidthValue === 'off')
        {
            $this->addFieldAfter('bandwidth', $this->getCustomBandwidthField());

        }

        $diskSpaceValue  = $this->getRequestValue('formData')['diskspace'];
        if ($diskSpaceValue === 'off')
        {
            $this->addFieldAfter('diskspace', $this->getCustomDiskSpacehField());
        }

        $sslValue = $this->getRequestValue('formData')['ssl'];
        if ($sslValue === 'on')
        {
            $this->addFieldAfter('ssl', $this->getForceSslSwitcher());
        }

        if ($diskSpaceValue === 'off' || $bandwidthValue === 'off' || $sslValue === 'on')
        {
            $this->dataProvider->reload();
            $this->loadDataToForm();
        }
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
