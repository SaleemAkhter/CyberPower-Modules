<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;


class Add extends BaseTabsForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\AddonDomains());

        $domain     = (new Fields\Text('domain'))->addValidator(new Validator\DomainRegrex());
        $bandwidth  = (new ActionSwitcher('bandwidth'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()');
        $diskspace  = (new ActionSwitcher('diskspace'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()');
        $ssl        = (new ActionSwitcher('ssl'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()');
        $cgi        = new Fields\Switcher('cgi');
        $php        = new Fields\Switcher('php');

        $this->addField($domain)
            ->addField($bandwidth)
            ->addField($diskspace)
            ->addField($ssl)
            ->addField($cgi)
            ->addField($php);


        $settingsRepository =  new Repository();
        $productConfiguration = $settingsRepository->getProductSettings($this->getWhmcsParamByKey('packageid'));

        if($productConfiguration['package'] != "custom" || $productConfiguration['dnscontrol'] == "on")
        {
            $localMail  = (new Fields\Switcher('localMail'))->setDescription('description');
            $this->addField($localMail);
        }

        $this->loadDataToForm();
    }

    public function getForceSslSwitcher()
    {
        return new Fields\Switcher('forceSsl');
    }

    public function getCustomBandwidthField()
    {
        $field = new Fields\Text('bandwidthCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }

    public function getCustomDiskSpacehField(){
        $field = new Fields\Text('diskspaceCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
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
