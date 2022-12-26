<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CreateReseller\Pages;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CreateReseller\Providers\Reseller;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use \ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\HtmlField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;

class Add extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'addForm';
    protected $name = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {

        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Reseller());
        $this->replaceClasses(['shadow1','p-20','col-lg-8']);
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
        if(empty($data['availablepackages'])){
            $url=$this->getAddPackageURL();
            $message='Sorry, it seems that there are no reseller packages created yet. Would you like to create one?';
            $this->addField((new HtmlField('aa'))->setRawHtml('<p>'.$message.'</p>'));
            $submitButton = new ButtonSubmitForm();
            $submitButton->setFormId($this->id)->setTitle('Create Reseller Package');
            $submitButton->runInitContentProcess();
            $submitButton->setHtmlAttributes(['@click'=>'redirectToUrl(\'' . $url . '\', $event)']);
            $this->setSubmit($submitButton);
        }else{
            $this->addSection($this->getUsernameSection())
            ->addSection($this->getPasswordSection())
            ->addSection($this->getDomainSection())
            ->addSection($this->getPackagesSection())
            ->addSection($this->getIpSection());
        }

        $this->loadDataToForm();


    }
    protected function getUsernameSection()
    {

        $usernameSection = new FormGroupSection('usernameSection');

        $usernameSection->addField((new Fields\Text('username'))->addValidator(new Validator\Alphanumeric())->addValidator(new Validator\UsernameLength()))
                        ->addField((new Fields\Text('email'))->addValidator(new Validator\Email()));

        return $usernameSection;
    }
    protected function getPasswordSection()
    {

        $passwordSection = new FormGroupSection('passwordSection');
        $password = new PasswordGenerateExtended('password');
        $password->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }
    protected function getDomainSection()
    {
        $domainSection = (new FormGroupSection('domainSection'))
            ->addField((new Fields\Text('domain'))
            ->addValidator(new Validator\DomainRegrex()));

        return $domainSection;
    }
    protected function getPackagesSection()
    {
        $packagesSection = (new FormGroupSection('packagesSection'))
            ->addField((new Fields\Select('package'))
                ->addHtmlAttribute('bi-event-change', 'initReloadModal'));

        return $packagesSection;
    }
    protected function getIpSection()
    {
        $ipSection = (new FormGroupSection('ipSection'))
            ->addField((new Fields\Select('ip'))
                ->addHtmlAttribute('bi-event-change', 'initReloadModal'));

        return $ipSection;
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
    protected function getAddPackageURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'ResellerPackages',
            'mg-action'=>'add'
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }


}
