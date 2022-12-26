<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserPackages\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserPackages\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\UnlimitedSwitcher;

class Add extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\Packages());
        $this->replaceClasses(['shadow1','p-20','col-lg-8']);
        $this->addField((new Fields\Text('name'))->addValidator(new Validator\Alphanumeric()))
        ->addField((new UnlimitedSwitcher('bandwidthUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'bandwidth\')'))
        ->addField((new UnlimitedSwitcher('diskspaceUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'diskspace\')'))
        ->addField((new UnlimitedSwitcher('inodeUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'inode\')'))
        ->addField((new UnlimitedSwitcher('vdomainsUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'vdomains\')'))
        ->addField((new UnlimitedSwitcher('nsubdomainsUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nsubdomains\')'))
        ->addField((new UnlimitedSwitcher('nemailsUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemails\')'))
        ->addField((new UnlimitedSwitcher('nemailfUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailf\')'))
        ->addField((new UnlimitedSwitcher('nemailmlUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailml\')'))
        ->addField((new UnlimitedSwitcher('nemailrUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailr\')'))
        ->addField((new UnlimitedSwitcher('mysqlUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'mysql\')'))
        ->addField((new UnlimitedSwitcher('domainptrUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'domainptr\')'))
        ->addField((new UnlimitedSwitcher('ftpUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'ftp\')'))
        ->addField((new UnlimitedSwitcher('uemail_daily_limitUnlimited'))->setDefaultValue('on')->addHtmlAttribute('@change', 'changeUnlimitedFields(\'uemail_daily_limit\')'))
        ->addField( new Fields\Switcher('aftp'))
        ->addField( new Fields\Switcher('cgi'))
        ->addField((new Fields\Switcher('php'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('spam'))->setDefaultValue('on'))
        ->addField( new Fields\Switcher('catchall'))
        ->addField( (new Fields\Switcher('ssl'))->setDefaultValue('on'))
        ->addField( new Fields\Switcher('ssh'))
        ->addField( (new Fields\Switcher('cron'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('sysinfo'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('login_keys'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('dnscontrol'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('suspend_at_limit'))->setDefaultValue('on'))
        ->addField( (new Fields\Switcher('jailedhome'))->setDefaultValue('on'));
        // ->addField( new Fields\Switcher('skin'))
        // ->addField( new Fields\Switcher('language'));




        $settingsRepository =  new Repository();
        $productConfiguration = $settingsRepository->getProductSettings($this->getWhmcsParamByKey('packageid'));

        // if($productConfiguration['package'] != "custom" || $productConfiguration['dnscontrol'] == "on")
        // {
        //     $localMail  = (new Fields\Switcher('localMail'))->setDescription('description');
        //     $this->addField($localMail);
        // }
        $script="alert('a');";
        $this->setScriptHtml($script);
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
