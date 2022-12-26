<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Email;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Fields\CustomSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\CommonName;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;

class LetsEncrypt extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;
    protected $id    = 'letsEncryptForm';
    protected $name  = 'letsEncryptForm';
    protected $title = 'letsEncryptFormTitle';
    protected $subdomains=['ftp','mail','pop','smtp','www'];
    const LETS_ENCRYPT  = 'letsEncrypt';
    protected $domain='';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-wp-details';

    public function initContent()
    {
        $this->addDefaultActions(self::LETS_ENCRYPT);
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\Ssl());

        $this->domain=$this->dataProvider->getDomain();

        $this->loadBasicSection()
            ->loadDataToForm();
    }
    public function returnAjaxData()
    {
        $data = [];
        $this->loadRequestObj();
        // $this->reset();
        if(isset($_POST['formData'])){
            return $this->dataProvider->letsEncrypt();
        }else{
            $options=$this->dataProvider->sslOptions();
            $opts=array_merge($options['dnsproviders']['data'],['local'=>['name'=>'Local','credentials'=>[],'additional_configuration'=>[]]]);
            $this->data=[
                'options'=>$options,
                'dnsprovidersoptions'=>$opts,
                'dnsprovider'=>'local',
                'setting'=>$options['dnsproviders']['settings'],
                'subdomains'=>$options['LETSENCRYPT_OPTIONS'],
                'wildcard_subdomains'=>$options['LETSENCRYPT_WC_OPTIONS'],
                'domain'=>$options['WWW_DOMAIN'],
                'forcessl'=>$options['FORCE_SSL_CHECKED']=='checked'?true:false
            ];

        }

        return (new RawDataJsonResponse(['data' => $this->data]));
    }
    public function loadBasicSection()
    {

        $basicSection = (new Sections\TabSection('basicSection'))->setTitle('basicSectionTitle');
        $section = new Sections\RawSection('basicSection');
        $commonname = (new CommonName('commonname'))->replaceClasses(['lu-form-group', 'lu-col-md-6','ml-10']);

        $section->addField($commonname);
        $selectedDomain = $this->getDomainData();
        $keysize        = (new Fields\Select('keysize'))->notEmpty()
            ->setDefaultValue($selectedDomain)
            ->addHtmlAttribute('bi-event-change', "initReloadModal");
        $certificatetype        = (new Fields\Select('certificatetype'))->notEmpty()
            ->setDefaultValue($selectedDomain)
            ->addHtmlAttribute('bi-event-change', "initReloadModal");

        $domains=[$this->domain];

        foreach ($this->subdomains as $key => $subdomains) {
            $domains[]=$subdomains.".".$this->domain;
        }
        $entries = (new Fields\Switcher('entries'));

        // foreach ($variable as $key => $value) {
        //     // code...
        // }



        $forcessl = new Fields\Switcher('forcessl');


        $section->addField($keysize)
            ->addField($certificatetype)
            ->addField($entries)
            ->addField($forcessl);

        $basicSection->addSection($section);
        $this->addSection($basicSection);

        return $this;
    }

    public function loadEntriesSection()
    {
        $entriesSection = (new Sections\TabSection('entriesSection'))->setTitle('entriesSectionTitle');
        $section = new Sections\RawSection('entriesSectionRaw');

        $selectedDomain = $this->getDomainData();
        $switchersData = $this->dataProvider->loadSwitchers($selectedDomain);

        if(!empty($switchersData))
        {
            foreach($switchersData as $elem => $value)
            {
                $section->addField((new Fields\Switcher('entries_'. $value))->setRawTitle( $value));
            }
        }
        $entriesSection->addSection($section);
        $this->addSection($entriesSection);

        return $this;
    }

    protected function reloadFormStructure()
    {
        $this->loadEntriesSection();
        $this->dataProvider->reload();
        $this->loadDataToForm();
    }

    private function getDomainData()
    {
        if(empty($this->getDataFromRequest()['domains']))
        {
            return $this->getWhmcsParamByKey('domain');
        }
        else
        {
            return $this->getDataFromRequest()['domains'];
        }
    }

    private function getDataFromRequest()
    {
        return $this->getRequestValue('formData');
    }

    public function getDomainName()
    {
        return $this->domain;
    }
    public function getSubdomains()
    {
        $domains=[$this->domain];

        foreach ($this->subdomains as $key => $subdomains) {
            $domains[]=$subdomains.".".$this->domain;
        }
        return $domains;
    }
    public function getFormAction()
    {
        // http://cyberpower.test/clientarea.php?action=productdetails&id=7&mg-page=MxRecords&modop=custom&a=management&loadData=createRecordForm&namespace=ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_MxRecords_Forms_Create&index=createRecordForm&ajax=1&mgformtype=create
        //
        //
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'Ssl',
            'loadData'=>'letsEncryptForm',
            'namespace'=>'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Ssl_Forms_LetsEncrypt',
            'mg-action'     => 'letsEncrypt',
            'index'=>'letsEncryptForm',
            'ajax'=>1,
            'mgformtype'=>'letsEncrypt'

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
}

