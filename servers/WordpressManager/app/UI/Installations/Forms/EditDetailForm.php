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
use ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\FileList;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\CronField;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\UpdateButtonsField;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationUpdateForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Sections\InstallationInfo;
use ModulesGarden\WordpressManager\App\UI\Installations\Sections\FormTitleSection;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\TitleField;
/**
 * Description of UpdateInstallation
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class EditDetailForm extends BaseStandaloneForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    use main\App\Http\Client\BaseClientController;
    use main\Core\UI\Traits\Buttons;
    protected $id = 'editDetailForm';
    // protected $modal;



    public function initContent()
    {
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        // $this->initIds('installationCreateForm');
        if($this->isLimitReached())
        {
            $this->modal->removeActionButtonByIndex('baseAcceptButton');
            return;
        }
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new InstallationProvider());
        $this->tabDetails();
        // $submitButton = new ButtonSubmitForm();
        // $submitButton->setFormId($this->id);
        // $submitButton->runInitContentProcess();
        // // debug($submitButton);die();
        // $this->setSubmit($submitButton);
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

    private function tabDetails()
    {
        $repository = new HostingRepository;
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return;
        }
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content     = new Sections\BoxSection();
        $maintitleSection = new Sections\RawSection('mainTitleSection');
        $rightSide   = new HalfPageSection('rightSide');
        $leftSide    = new HalfPageSection('leftSide');
        $mainSection = new Sections\RawSection('mainSection');
        $content->setMainContainer($this->mainContainer);
        $rightSide->setMainContainer($this->mainContainer);
        $mainSection->setMainContainer($this->mainContainer);
        if (!$this->formData['hostingId'])
        {
            $this->formData['hostingId'] = key($hostings);
        }
        $field = new TitleField('Installation Details');
        $maintitleSection->addField($field);
        //softdirectory
        $field = new Fields\Text('softdirectory');
        $field->setDescription('description');
        $leftSide->addField($field);

        //softdirectory
        $field = new Fields\Text('url');
        $field->setDescription('description');
        $rightSide->addField($field);
        //softdb
        $field = new Fields\Text('db');
        $field->notEmpty();
        $field->setDescription('description');
        $leftSide->addField($field);

        //softdbuser
        $field = new Fields\Text('dbUser');
        $field->notEmpty();
        $rightSide->addField($field);

        //softpass
        $field = new Fields\Text('dbPass');
        $field->notEmpty();
        $leftSide->addField($field);

        //softhost
        $field = new Fields\Text('dbHost');
        $field->notEmpty();
        $rightSide->addField($field);
         //site_name
        // $field = new Fields\Text('siteName');
        // $field->setDescription('description');
        // $field->notEmpty();
        // $leftSide->addField($field);

        // //site_desc
        // $field = (new Fields\Text('site_desc'))->setDescription('description');
        // $rightSide->addField($field);

        //auto_upgrade_plugins
        $field = (new Fields\Switcher('disable_notify_update'))->setDescription('description');
        $leftSide->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('auto_upgrade_plugins'))->setDescription('description');
        $leftSide->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('auto_upgrade_themes'))->setDescription('description');
        $leftSide->addField($field);
        $field = (new UpdateButtonsField('updatethemeandplugin'));

        $leftSide->addField($field);
        //eu_auto_upgrade
        $field = (new Fields\Select('eu_auto_upgrade'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Do not auto upgrade"),
            1 => sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
            2 => sl("lang")->abtr("Upgrade to Minor versions only"),
        ]);
        $rightSide->addField($field);




        $field = (new Fields\Select('backup_location'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Default"),
            "-1" => sl("lang")->abtr("Local Folder"),

        ]);

        $leftSide->addField($field);


        $field = (new Fields\Select('auto_backup'))->addHtmlAttribute('id','auto_backup')->addHtmlAttribute('onchange','show_backup();')->setFormGroupClass("mt-110")->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Don't backup"),
            "daily" => sl("lang")->abtr("Once a day"),
            "weekly" => sl("lang")->abtr("Once a week"),
            "monthly" => sl("lang")->abtr("Once a month"),
            "custom" => sl("lang")->abtr("Custom"),
        ]);
        $rightSide->addField($field);

        $field = (new Fields\Select('auto_backup_rotation'))->addHtmlAttribute('id','auto_backup_rotation')->setDescription('description');
        if($data['auto_backup_rotation']==""){
            $field->addHtmlAttribute('disabled','disabled');
        }
        $field->setAvailableValues([
            0 => "Unlimited",
            1=>1,
            2=>2,
            3=>3,
            4=>4,
            5=>5,
            6=>6,
            7=>7,
            8=>8,
            9=>9,
            10=>10
        ])->setSelectedValue(4);
        $leftSide->addField($field);
        $customcron=($data['auto_backup']=="custom");
        $field = (new CronField('customcronfield'))->setVisibility($customcron)
        ->setCronData($data['auto_backup_crontime']);

        $rightSide->addField($field);




        if(count($data['add_to_fileindex'])){
            $field = (new FileList('add_to_fileindex'))->setFiles($data['add_to_fileindex']);

            $rightSide->addField($field);

            $field = (new Fields\Select('select_files_backup'))->setDescription('description');
            $field->setAvailableValues([
                0 => sl("lang")->abtr("No (Backup all files and folders inside installation directory excluding subdomains)"),
                "-1" => sl("lang")->abtr("Yes (Backup standard application files/folders along with above selected files/folders)"),

            ]);
            $leftSide->addField($field);
        }


        //language
        // $json  = new Json('softaculousLanguages.json', ModuleConstants::getDevConfigDir());
        // $field = new Fields\Select('language');
        // $field->setAvailableValues((array)$json->get());
        // $field->setDescription('description')->setFormGroupClass("mt-30");
        // $field->setDefaultValue((new main\App\Helper\LangConveter())->convert());
        // $rightSide->addField($field);

        // //dbprefix
        // $field = new Fields\Text('dbprefix');
        // $field->setDescription('description');
        // $leftSide->addField($field);
        // //multisite on off
        // $field = (new Fields\Switcher('multisite'))->setDescription('description');
        // $rightSide->addField($field);



        //auto_upgrade_themes
        $field = (new Fields\Switcher('auto_upgrade_themes'))->setDescription('description');
        $leftSide->addField($field);



        $content->addSection($maintitleSection);
        $content->addSection($leftSide);
        $content->addSection($rightSide);
        $content->addSection($mainSection);
        $section->addSection($content);
        $this->addSection($section);

        //Add other tab
        $this->tabOther();
    }

    private function tabOther()
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content = new Sections\BoxSection();
        $rightSide   = new HalfPageSection('rightSide');
        $leftSide    = new HalfPageSection('leftSide');
        $rightSide->setMainContainer($this->mainContainer);
        $leftSide->setMainContainer($this->mainContainer);

        $pluginssection=new Sections\RawSection();
        $pluginleftSide    = new HalfPageSection('pluginleftSide');
        $pluginrightSide   = new HalfPageSection('pluginrightSide');
        $pluginleftSide->setMainContainer($this->mainContainer);
        $pluginrightSide->setMainContainer($this->mainContainer);



        $maintitleSection = new Sections\RawSection('mainTitleSection');

        $field = new TitleField('Admin Account');
        $maintitleSection->addField($field);
        //

//admin_username
        $leftSide->addField((new Fields\Text('admin_username'))->setDescription('description'));
        // //admin_email
        // $field = new Fields\Text('admin_email');
        // $field->setDescription('description');
        // $leftSide->addField($field);
        //admin_pass

        $rightSide->addField((new Fields\PasswordGenerate('admin_pass'))->setDescription('description'));

        $leftSide->addField((new Fields\Text('signon_username'))->setDescription('description'));




        $field = new TitleField('Install Plugin(s)');
        $pluginleftSide->addField($field);
        $field = (new Fields\Switcher('loginizer'))->setDescription('description');
        $pluginleftSide->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('classic-editor'))->addClass("mt-70")->setDescription('description');
        $pluginrightSide->addField($field);

        $pluginssection->addSection($pluginleftSide);
        $pluginssection->addSection($pluginrightSide);

        $content->addSection($maintitleSection);
        $content->addSection($leftSide);
        $content->addSection($rightSide);
        $content->addSection($pluginssection);
        $section->addSection($content);
        $this->addSection($section);

    }
    public function getInfoSectionHtml(){
         /*TODO : Make additional section working so that can put info below before submit button*/
         return  "";
    }

}
