<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Filters\Text;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\UnlimitedSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;


class Edit extends BaseForm implements ClientArea
{
    protected $id = 'editForm';
    protected $name = 'editForm';
    protected $title = 'editForm';
    protected $bandwidthSwitcher;
    protected $usageSwitcher;

    public function initContent()
    {
        $this->loadLang();

        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\PackagesEdit());

        $this->loadDataToForm();
        $this->addSwitcherSection();
        $this->addField(new Fields\Hidden('name'));

        // $this->addUsage();
        // $this->addBandwidth();
        // $this->addUnlimitedTypeFields();

        $this->isSslEnabled();
        $this->loadDataToForm();
    }




    private function addBandwidth()
    {
        $formData = sl('request')->get('formData');
        // debug($formData);die();
        if ($this->dataProvider->getValueById('bandwidthUnlimited') === 'on')
        {
            if ($formData && $formData['bandwidthUnlimited'] === 'off')
            {
                $this->addFieldAfter('bandwidthUnlimited', $this->getBandwidthField());
            }
        }
        else
        {
            if ($formData && $formData['bandwidthUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('bandwidthUnlimited', $this->getBandwidthField());
        }
    }

    private function getBandwidthField()
    {
        $bandwidth = (new Fields\Text('bandwidth'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $bandwidth;
    }

    private function getUsageField()
    {
        $usage = (new Fields\Text('diskspace'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $usage;
    }
    private function getInodeField()
    {
        $usage = (new Fields\Text('inode'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $usage;
    }
    private function getVdomainsField()
    {
        $usage = (new Fields\Text('vdomains'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $usage;
    }
    private function getNsubdomainsField()
    {
        $usage = (new Fields\Text('nsubdomains'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $usage;
    }
    private function getOptTextField($name)
    {
        $usage = (new Fields\Text($name))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
        return $usage;
    }


    private function addUsage()
    {
        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('usageUnlimited') === 'on')
        {
            if ($formData && $formData['usageUnlimited'] === 'off')
            {
                $this->addFieldAfter('usageUnlimited', $this->getUsageField());
            }
        }
        else
        {
            if ($formData && $formData['usageUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('usageUnlimited', $this->getUsageField());
        }
    }
    private function addUnlimitedTypeFields()
    {
        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('inodeUnlimited') === 'on')
        {
            if ($formData && $formData['inodeUnlimited'] === 'off')
            {
                $this->addFieldAfter('inodeUnlimited', $this->getOptTextField('inode'));
            }
        }
        else
        {
            if ($formData && $formData['inodeUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('inodeUnlimited', $this->getOptTextField('inode'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('vdomainsUnlimited') === 'on')
        {
            if ($formData && $formData['vdomainsUnlimited'] === 'off')
            {
                $this->addFieldAfter('vdomainsUnlimited', $this->getOptTextField('vdomains'));
            }
        }
        else
        {
            if ($formData && $formData['vdomainsUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('vdomainsUnlimited', $this->getOptTextField('vdomains'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('nsubdomainsUnlimited') === 'on')
        {
            if ($formData && $formData['nsubdomainsUnlimited'] === 'off')
            {
                $this->addFieldAfter('nsubdomainsUnlimited', $this->getOptTextField('nsubdomains'));
            }
        }
        else
        {
            if ($formData && $formData['nsubdomainsUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('nsubdomainsUnlimited', $this->getOptTextField('nsubdomains'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('nemailsUnlimited') === 'on')
        {
            if ($formData && $formData['nemailsUnlimited'] === 'off')
            {
                $this->addFieldAfter('nemailsUnlimited', $this->getOptTextField('nemails'));
            }
        }
        else
        {
            if ($formData && $formData['nemailsUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('nemailsUnlimited', $this->getOptTextField('nemails'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('nemailfUnlimited') === 'on')
        {
            if ($formData && $formData['nemailfUnlimited'] === 'off')
            {
                $this->addFieldAfter('nemailfUnlimited', $this->getOptTextField('nemailf'));
            }
        }
        else
        {
            if ($formData && $formData['nemailfUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('nemailfUnlimited', $this->getOptTextField('nemailf'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('nemailmlUnlimited') === 'on')
        {
            if ($formData && $formData['nemailmlUnlimited'] === 'off')
            {
                $this->addFieldAfter('nemailmlUnlimited', $this->getOptTextField('nemailml'));
            }
        }
        else
        {
            if ($formData && $formData['nemailmlUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('nemailmlUnlimited', $this->getOptTextField('nemailml'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('nemailrUnlimited') === 'on')
        {
            if ($formData && $formData['nemailrUnlimited'] === 'off')
            {
                $this->addFieldAfter('nemailrUnlimited', $this->getOptTextField('nemailr'));
            }
        }
        else
        {
            if ($formData && $formData['nemailrUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('nemailrUnlimited', $this->getOptTextField('nemailr'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('mysqlUnlimited') === 'on')
        {
            if ($formData && $formData['mysqlUnlimited'] === 'off')
            {
                $this->addFieldAfter('mysqlUnlimited', $this->getOptTextField('mysql'));
            }
        }
        else
        {
            if ($formData && $formData['mysqlUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('mysqlUnlimited', $this->getOptTextField('mysql'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('domainptrUnlimited') === 'on')
        {
            if ($formData && $formData['domainptrUnlimited'] === 'off')
            {
                $this->addFieldAfter('domainptrUnlimited', $this->getOptTextField('domainptr'));
            }
        }
        else
        {
            if ($formData && $formData['domainptrUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('domainptrUnlimited', $this->getOptTextField('domainptr'));
        }

        $formData = sl('request')->get('formData');
        if ($this->dataProvider->getValueById('ftpUnlimited') === 'on')
        {
            if ($formData && $formData['ftpUnlimited'] === 'off')
            {
                $this->addFieldAfter('ftpUnlimited', $this->getOptTextField('ftp'));
            }
        }
        else
        {
            if ($formData && $formData['ftpUnlimited'] === 'on')
            {
                return;
            }
            $this->addFieldAfter('ftpUnlimited', $this->getOptTextField('ftp'));
        }
    }

    private function addSwitcherSection()
    {
        $this->loadDataToForm();

        $data= $this->dataProvider->getData();
        $this->addField((new Fields\Text('name'))->addValidator(new Validator\Alphanumeric()))
        ->addField((new UnlimitedSwitcher('bandwidthUnlimited'))->setDefaultValue($data['bandwidthUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'bandwidth\')')->setTextFieldValue($data['bandwidth']))
        ->addField((new UnlimitedSwitcher('diskspaceUnlimited'))->setDefaultValue($data['diskspaceUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'diskspace\')')->setTextFieldValue($data['diskspace']))
        ->addField((new UnlimitedSwitcher('inodeUnlimited'))->setDefaultValue($data['inodeUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'inode\')')->setTextFieldValue($data['inode']))
        ->addField((new UnlimitedSwitcher('vdomainsUnlimited'))->setDefaultValue($data['vdomainsUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'vdomains\')')->setTextFieldValue($data['vdomains']))
        ->addField((new UnlimitedSwitcher('nsubdomainsUnlimited'))->setDefaultValue($data['nsubdomainsUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nsubdomains\')')->setTextFieldValue($data['nsubdomains']))
        ->addField((new UnlimitedSwitcher('nemailsUnlimited'))->setDefaultValue($data['nemailsUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemails\')')->setTextFieldValue($data['nemails']))
        ->addField((new UnlimitedSwitcher('nemailfUnlimited'))->setDefaultValue($data['nemailfUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailf\')')->setTextFieldValue($data['nemailf']))
        ->addField((new UnlimitedSwitcher('nemailmlUnlimited'))->setDefaultValue($data['nemailmlUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailml\')')->setTextFieldValue($data['nemailml']))
        ->addField((new UnlimitedSwitcher('nemailrUnlimited'))->setDefaultValue($data['nemailrUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailr\')')->setTextFieldValue($data['nemailr']))
        ->addField((new UnlimitedSwitcher('mysqlUnlimited'))->setDefaultValue($data['mysqlUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'mysql\')')->setTextFieldValue($data['mysql']))
        ->addField((new UnlimitedSwitcher('domainptrUnlimited'))->setDefaultValue($data['domainptrUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'domainptr\')')->setTextFieldValue($data['domainptr']))
        ->addField((new UnlimitedSwitcher('ftpUnlimited'))->setDefaultValue($data['ftpUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'ftp\')')->setTextFieldValue($data['ftp']))
        ->addField( new Fields\Switcher('cgi'))
        ->addField( (new Fields\Switcher('php')))
        ->addField( (new Fields\Switcher('spam')))
        ->addField( new Fields\Switcher('catchall'))
        ->addField( (new Fields\Switcher('ssl')))
        ->addField( new Fields\Switcher('ssh'))
        ->addField( new Fields\Switcher('jailedhome'))
        ->addField( new Fields\Switcher('cron'))
        ->addField( (new Fields\Switcher('dnscontrol')))
        ->addField( (new Fields\Switcher('suspend_at_limit')));
    }

    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();

        $this->isSslEnabled();

        $this->loadDataToForm();
    }

    protected function isSslEnabled()
    {
        if ($this->dataProvider->getValueById('secureSsl') === 'on') {
            $forceSsl = (new Fields\Switcher('forceSsl'))->setDescription('forceSslDescription');
            $this->addField($forceSsl);
        } else {
            $this->removeField('forceSsl');
        }
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

