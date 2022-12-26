<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\UnlimitedSwitcher;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;

class UserEditUsage extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'editUsageForm';
    protected $name = 'editUsageForm';
    protected $title = 'Manually Change Settings';
    // public function __construct($baseId = null)
    // {
    //     parent::__construct($baseId);
    // }
    public function  getFormContainerClass(){
        return "lu-col-md-8";
    }
    public function initContent()
    {

        $this->addReplacement();
        $this->setRawTitle("<h6 class='pt-20'><strong>Manually Change Settings</strong></h6>");
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\UsersEdit());
        $this->addClass('lu-col-md-8');
        $this->loadDataToForm();
        $this->addSwitcherSection();
        $this->addField(new Fields\Hidden('user'));
        $this->addField(new Fields\Hidden('editUsage'));
        $this->addUsage();
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
                // $this->addFieldAfter('bandwidthUnlimited', $this->getBandwidthField());
            }
        }
        else
        {
            if ($formData && $formData['bandwidthUnlimited'] === 'on')
            {
                // return;
            }
            // $this->addFieldAfter('bandwidthUnlimited', $this->getBandwidthField());
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
        //  $this->addFieldAfter('usageUnlimited', $this->getUsageField());
        // $formData = sl('request')->get('formData');
        // if ($this->dataProvider->getValueById('usageUnlimited') === 'on')
        // {
        //     if ($formData && $formData['usageUnlimited'] === 'off')
        //     {
        //         $this->addFieldAfter('usageUnlimited', $this->getUsageField());
        //     }
        // }
        // else
        // {
        //     if ($formData && $formData['usageUnlimited'] === 'on')
        //     {
        //         // return;
        //     }
        //     $this->addFieldAfter('usageUnlimited', $this->getUsageField());
        // }
    }


    protected function getPackageFieldsSection()
    {
        $selectFieldsSection = (new FormGroupSection('packagesSection'))
            ->addField(new Fields\Select('package'));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
    private function addSwitcherSection()
    {
        $this->loadDataToForm();

        $data= $this->dataProvider->getData();
        $this->addField((new UnlimitedSwitcher('bandwidthUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'bandwidth\')')->setTextFieldValue($data['bandwidth']))
        ->addField((new UnlimitedSwitcher('usageUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'usage\')')->setTextFieldValue($data['usage']))
        ->addField((new UnlimitedSwitcher('inodeUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'inode\')')->setTextFieldValue($data['inode']))
        ->addField((new UnlimitedSwitcher('vdomainsUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'vdomains\')')->setTextFieldValue($data['vdomains']))
        ->addField((new UnlimitedSwitcher('nsubdomainsUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nsubdomains\')')->setTextFieldValue($data['nsubdomains']))
        ->addField((new UnlimitedSwitcher('nemailsUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemails\')')->setTextFieldValue($data['nemails']))
        ->addField((new UnlimitedSwitcher('nemailfUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailf\')')->setTextFieldValue($data['nemailf']))
        ->addField((new UnlimitedSwitcher('nemailmlUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailml\')')->setTextFieldValue($data['nemailml']))
        ->addField((new UnlimitedSwitcher('nemailrUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'nemailr\')')->setTextFieldValue($data['nemailr']))
        ->addField((new UnlimitedSwitcher('mysqlUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'mysql\')')->setTextFieldValue($data['mysql']))
        ->addField((new UnlimitedSwitcher('domainptrUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'domainptr\')')->setTextFieldValue($data['domainptr']))
        ->addField((new UnlimitedSwitcher('ftpUnlimited'))->addHtmlAttribute('@change', 'changeUnlimitedFields(\'ftp\')')->setTextFieldValue($data['ftp']))
        ->addField((new UnlimitedSwitcher('uemail_daily_limitUnlimited'))->setDefaultValue($data['uemail_daily_limitUnlimited'])->addHtmlAttribute('@change', 'changeUnlimitedFields(\'uemail_daily_limit\')')->setTextFieldValue($data['uemail_daily_limit']))
        ->addField( (new Fields\Switcher('aftp'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('cgi'))->addClass('w-50'))
        ->addField((new Fields\Switcher('php'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('spam'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('catchall'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('ssl'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('ssh'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('jailedhome'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('cron'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('sysinfo'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('login_keys'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('dnscontrol'))->addClass('w-50'))
        ->addField( (new Fields\Switcher('suspend_at_limit'))->addClass('w-50'))
        ->addField((new Fields\Text('ns1'))->addClass('w-50'))
        ->addField((new Fields\Text('ns2'))->addClass('w-50'));
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
