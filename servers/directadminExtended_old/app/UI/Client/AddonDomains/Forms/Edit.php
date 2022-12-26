<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Filters\Text;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;

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
        $this->setProvider(new Providers\AddonDomainsEdit());

        $this->loadDataToForm();
        $this->addLocalMailField();
        $this->addSwitcherSection();
        $this->addField(new Fields\Hidden('domain'));

        $this->addUsage();
        $this->addBandwidth();
        $this->addPhpVersion();

        $this->isSslEnabled();
        $this->loadDataToForm();
    }

    private function addPhpSelectorField()
    {
        $phpSelect = new Fields\Select('php1');
        return $phpSelect;
    }

    private function addLocalMailField()
    {
        $settingsRepository = new Repository();
        $productConfiguration = $settingsRepository->getProductSettings($this->getWhmcsParamByKey('packageid'));

        if ($productConfiguration['package'] != "custom" || $productConfiguration['dnscontrol'] == "on") {
            $this->addField((new Fields\Switcher('localMail'))->setDescription('description'));
        }
    }

    private function addBandwidth()
    {
        $formData = sl('request')->get('formData');
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
        $usage = (new Fields\Text('diskUsage'))->addValidator(new Numeric())->setPlaceholder($this->lang->absoluteTranslate('unlimited'));
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

    public function addPhpVersion()
    {
        $formData = sl('request')->get('formData');

        if ($this->dataProvider->getValueById('phpAccess') === 'on')
        {
            if ($formData && $formData['phpAccess'] === 'off')
            {
                $this->removeField('php1');
                return;
            }
            $this->addFieldAfter('phpAccess', $this->addPhpSelectorField());
        }
        else
        {
            if ($formData && $formData['phpAccess'] === 'on')
            {
                $this->addFieldAfter('phpAccess', $this->addPhpSelectorField());
            }
        }
    }

    private function addSwitcherSection()
    {
        $secureSsl = (new Fields\Switcher('secureSsl'))->addHtmlAttribute('@change', 'initReloadModal()');
        $phpAccess = (new Fields\Switcher('phpAccess'))->addHtmlAttribute('@change', 'initReloadModal()');;
        $redirect = new Fields\Switcher('redirect');
        $cgiAccess = new Fields\Switcher('cgiAccess');

        $usageUnlimited = (new Fields\Switcher('usageUnlimited'))->addHtmlAttribute('@change', 'initReloadModal()');
        $bandwidthUnlimited = (new Fields\Switcher('bandwidthUnlimited'))->addHtmlAttribute('@change', 'initReloadModal()');

        $this->addField($usageUnlimited);
        $this->addField($bandwidthUnlimited);
        $this->addField($secureSsl);
        $this->addField($phpAccess);
        $this->addField($redirect);
        $this->addField($cgiAccess);
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

