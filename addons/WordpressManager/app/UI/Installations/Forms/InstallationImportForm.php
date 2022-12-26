<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\Decorator;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\ImportProvider;
use ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\TitleField;
use ModulesGarden\WordpressManager\App\UI\Installations\Sections\RowSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\SoftwareSetup;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\PrefixedText;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\AutoDetectButtonField;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationImportForm extends BaseTabsForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    use main\App\Http\Client\BaseClientController;

    // protected $modal;
    public function validateSections($request)
    {

        foreach ($this->sections as $section)
        {
            $section->validateFields($request);
            $section->validateSections($request);
            if ($section->getValidationErrors())
            {
                $this->validationErrors = array_merge($this->validationErrors, $section->getValidationErrors());
            }

        }
        $this->validationErrors=[];
        return $this;
    }
    /**
     * InstallationCreateForm constructor.
     * @param main\App\UI\Installations\Modals\InstallationCreateModal $modal
     */
    // public function __construct(main\App\UI\Installations\Modals\InstallationCreateModal $modal)
    // {
    //     $this->modal    = $modal;

    //     parent::__construct();
    // }

    public function initContent()
    {
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->initIds('installationImportForm');
        if($this->isLimitReached())
        {
            $this->modal->removeActionButtonByIndex('baseAcceptButton');
            return;
        }
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new ImportProvider());
        $this->tabThisServer();
        $this->tabRemoteServer();
        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId($this->id);
        $submitButton->runInitContentProcess();
        // debug($submitButton);die();
        $this->setSubmit($submitButton);
        $this->loadDataToForm();
    }

    private function isLimitReached()
    {
        $repository = new HostingRepository;
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return true;
        }

        return false;
    }

    private function tabRemoteServer()
    {
        $repository = new HostingRepository;
        $request       = sl('request');
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return;
        }
        $loggedinuser=Helper\loggedinUsername();



        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);

        $section->setName(sl('lang')->T(__FUNCTION__));
        $content     = new Sections\RawSection();
        $sourcesection=(new Sections\BoxSection())->setId("sourceBox")->setTitle("Source")->setMainContainer($this->mainContainer);
        $sourceLeftSide    = (new HalfPageSection('sourceLeftSide'))->setId('sourceLeftSide');
        $sourceRightSide   = (new HalfPageSection('sourceRightSide'))->setId('sourceRightSide');



        $destinationsection=(new Sections\BoxSection())->setId("destinationBox")->setTitle("Destination")->setMainContainer($this->mainContainer);
        $destinationLeftSide    = (new HalfPageSection('destinationLeftSide'))->setId('destinationLeftSide');
        $destinationRightSide   = (new HalfPageSection('destinationRightSide'))->setId('destinationRightSide');


        $advancedSection     = (new Sections\BoxSection())->setId("advancedBox")->setTitle("Advanced Options")->enableCustomCollapse()->setMainContainer($this->mainContainer);

        $advancedLeftSide=(new HalfPageSection('advancedLeftSide'))->setId('advancedLeftSide');


        $field = new Fields\Hidden();
        $field->setName('hostingId');
        $field->setId('hostingId')->setDefaultValue($_GET['id']);
        $sourceLeftSide->addField($field);

        $field = new Fields\Text('domain');
        $field->notEmpty()->setHelpText('Enter a valid domain name. e.g. mydomain.com');
        $sourceLeftSide->addField($field);

        $field = new Fields\Select('protocol');
        $field->setAvailableValues(['ftp'=>'FTP','ftps'=>'FTPS','sftp'=>'SFTP']);
        $field->setDescription('description');
        $field->notEmpty();
        $sourceLeftSide->addField($field);

        $field = new Fields\Text('ftp_user');
        $field->setHelpText(sl('lang')->T("ftp_user_description"));
// 'The username of your FTP Account'
        $field->notEmpty();
        $sourceLeftSide->addField($field);

        $field = new Fields\Text('ftp_path');
        $field->setHelpText('Relative path to web accessible directory of user. e.g. /public_html');
        $field->notEmpty();
        $sourceLeftSide->addField($field);



        $field = new Fields\Text('server_host');
        $field->setDescription('description')->setHelpText(' .');
        $field->notEmpty();
        $sourceRightSide->addField($field);


        $field = new Fields\Text('port');
        $field->setDescription('description')->setDefaultValue('21');
        $field->notEmpty();
        $sourceRightSide->addField($field);


        $sourceRightSide->addField((new Fields\Password('ftp_pass'))->setHelpText('The Password of your FTP account'));


        $field = new Fields\Text('Installed_path');
        $field->setHelpText(' Installation directory e.g. blog if you have installed the script at /public_html/blog');
        $field->notEmpty();
        $sourceRightSide->addField($field);

        $field = new Fields\Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setDefaultValue(1);
        $destinationLeftSide->addField($field);
        $field = new Fields\Text('dest_directory');
        $field->setDescription('description');
        $field->notEmpty();
        $destinationLeftSide->addField($field);

        $hostingId=$_GET['id'];

        $this->reset()
            ->setHostingId($hostingId)
            ->setUserId($this->request->getSession('uid'));

        $this->subModule()->setUsername($loggedinuser);
        $domains=$this->subModule()->domain()->list();
        $domainlist=[];
        foreach ($domains as $key => $domain) {
            $domainlist[$domain]=$domain;
        }
        $field = (new Fields\Select('softdomain'))->setAvailableValues($domainlist);

        $destinationRightSide->addField($field);

        $dbName = "wp".UtilityHelper::generatePassword(3,'abcdefghijklmnopqrstuvwxyz123456789');

        $field = (new PrefixedText('softdb'))->setHelpText("Type the name of the database to be created for the installation")->setPrefix($loggedinuser."_");
        $field->notEmpty();
        $field->setDefaultValue($dbName);
        $advancedLeftSide->addField($field);

        $sourcesection->addSection($sourceLeftSide);
        $sourcesection->addSection($sourceRightSide);
        $content->addSection($sourcesection);

        $destinationsection->addSection($destinationLeftSide);
        $destinationsection->addSection($destinationRightSide);
        $content->addSection($destinationsection);

        $advancedSection->addSection($advancedLeftSide);

        $content->addSection($advancedSection);
        $section->addSection($content);
        $this->addSection($section);


    }

    private function tabThisServer()
    {
        $request       = sl('request');
        $loggedinuser=Helper\loggedinUsername();
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content = new Sections\RawSection();
        $buttonsection = (new Sections\RawSection())->addClass("text-right")->setMainContainer($this->mainContainer);
        $destinationsection=(new Sections\BoxSection())->setId("destinationBox")->setMainContainer($this->mainContainer);
        $destinationLeftSide    = (new HalfPageSection('destinationLeftSide'))->setId('destinationLeftSide');
        $destinationRightSide   = (new HalfPageSection('destinationRightSide'))->setId('destinationRightSide');

        $field = (new AutoDetectButtonField('autodetectinstallations'));


        $baseUrl = BuildUrl::getUrl('home', 'autodetect',['id'=>$_GET['id']]);

        $field->setUrl($baseUrl);

        $buttonsection->addField($field);
        //site_desc
        $field = new Fields\Select('localsoftproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setDefaultValue(1);
        $destinationLeftSide->addField($field);
        $field = new Fields\Text('localdest_directory');
        $field->setDescription('description');
        $field->notEmpty();
        $destinationLeftSide->addField($field);

        $hostingId=$_GET['id'];

        $this->reset()
            ->setHostingId($hostingId)
            ->setUserId($this->request->getSession('uid'));

        $this->subModule()->setUsername($loggedinuser);
        $domains=$this->subModule()->domain()->list();
        $domainlist=[];
        foreach ($domains as $key => $domain) {
            $domainlist[$domain]=$domain;
        }
        $field = (new Fields\Select('localsoftdomain'))->setAvailableValues($domainlist);

        $destinationRightSide->addField($field);

        $destinationsection->addSection($destinationLeftSide);
        $destinationsection->addSection($destinationRightSide);
        $content->addSection($buttonsection);
        $content->addSection($destinationsection);

        $section->addSection($content);
        $this->addSection($section);
    }
    public function installationPageLink()
    {
        return BuildUrl::getUrl('home', 'index',['id'=>$_GET['id']]);
    }

}
