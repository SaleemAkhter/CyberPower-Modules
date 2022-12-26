<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Providers;
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
            ->setProvider(new Providers\Packages());

        $this->addField((new Fields\Text('name'))->addValidator(new Validator\Alphanumeric()))
        ->addField((new ActionSwitcher('bandwidth'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('diskspace'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('inode'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('vdomains'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('nsubdomains'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('nemails'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('nemailf'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('nemailml'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('nemailr'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('mysql'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('domainptr'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField((new ActionSwitcher('ftp'))->setDefaultValue('on')->addHtmlAttribute('@change', 'initReloadModal()'))
        ->addField( new Fields\Switcher('cgi'))
        ->addField((new Fields\Switcher('php'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('spam'))->setDefaultValue('on'))
        ->addField( new Fields\Switcher('catchall'))
        ->addField( (new ActionSwitcher('ssl'))->setDefaultValue('on'))
        ->addField( new Fields\Switcher('ssh'))
        ->addField( new Fields\Switcher('jailedhome'))
        ->addField( (new Fields\Switcher('dnscontrol'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('suspend_at_limit'))->setDefaultValue('on'));
        // ->addField( new Fields\Switcher('skin'))
        // ->addField( new Fields\Switcher('language'));




        $settingsRepository =  new Repository();
        $productConfiguration = $settingsRepository->getProductSettings($this->getWhmcsParamByKey('packageid'));

        // if($productConfiguration['package'] != "custom" || $productConfiguration['dnscontrol'] == "on")
        // {
        //     $localMail  = (new Fields\Switcher('localMail'))->setDescription('description');
        //     $this->addField($localMail);
        // }

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
    public function getCustomInodeField(){
        $field = new Fields\Text('inodeCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomVDomainsField(){
        $field = new Fields\Text('vdomainsCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomNSubDomainsField(){
        $field = new Fields\Text('nsubdomainsCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomNEmailsField(){
        $field = new Fields\Text('nemailsCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomNEmailfField(){
        $field = new Fields\Text('nemailfCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomNEmailmlField(){
        $field = new Fields\Text('nemailmlCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomNEmailrField(){
        $field = new Fields\Text('nemailrCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomMysqlField(){
        $field = new Fields\Text('mysqlCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomDomainptrField(){
        $field = new Fields\Text('domainptrCustom');
        $field->addValidator(new Validator\Numeric());

        return $field;
    }
    public function getCustomFtpField(){
        $field = new Fields\Text('ftpCustom');
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
        $inodesValue  = $this->getRequestValue('formData')['inode'];
        if ($inodesValue === 'off')
        {
            $this->addFieldAfter('inode', $this->getCustomInodeField());
        }

        $vdomainsValue  = $this->getRequestValue('formData')['vdomains'];
        if ($vdomainsValue === 'off')
        {
            $this->addFieldAfter('vdomains', $this->getCustomVDomainsField());
        }

        $nsubdomainsValue  = $this->getRequestValue('formData')['nsubdomains'];
        if ($nsubdomainsValue === 'off')
        {
            $this->addFieldAfter('nsubdomains', $this->getCustomNSubDomainsField());
        }

        $nemailsValue  = $this->getRequestValue('formData')['nemails'];
        if ($nemailsValue === 'off')
        {
            $this->addFieldAfter('nemails', $this->getCustomNEmailsField());
        }

        $nemailfValue  = $this->getRequestValue('formData')['nemailf'];
        if ($nemailfValue === 'off')
        {
            $this->addFieldAfter('nemailf', $this->getCustomNEmailfField());
        }

        $nemailmlValue  = $this->getRequestValue('formData')['nemailml'];
        if ($nemailmlValue === 'off')
        {
            $this->addFieldAfter('nemailml', $this->getCustomNEmailmlField());
        }

        $nemailrValue  = $this->getRequestValue('formData')['nemailr'];
        if ($nemailrValue === 'off')
        {
            $this->addFieldAfter('nemailr', $this->getCustomNEmailrField());
        }

        $mysqlValue  = $this->getRequestValue('formData')['mysql'];
        if ($mysqlValue === 'off')
        {
            $this->addFieldAfter('mysql', $this->getCustomMysqlField());
        }

        $domainptrValue  = $this->getRequestValue('formData')['domainptr'];
        if ($domainptrValue === 'off')
        {
            $this->addFieldAfter('domainptr', $this->getCustomDomainptrField());
        }

        $ftpValue  = $this->getRequestValue('formData')['ftp'];
        if ($ftpValue === 'off')
        {
            $this->addFieldAfter('ftp', $this->getCustomFtpField());
        }


        if ($diskSpaceValue === 'off' || $bandwidthValue === 'off' || $inodesValue==='off' || $vdomainsValue === 'off' || $nsubdomainsValue === 'off' || $nemailsValue == 'off' || $nemailfValue == 'off' || $nemailmlValue == 'off' || $nemailrValue == 'off' || $mysqlValue == 'off' || $domainptrValue == 'off' || $ftpValue == 'off' )
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
