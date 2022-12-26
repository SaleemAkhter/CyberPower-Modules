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

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Settings\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Settings\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\MaxUploadsize;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Settings extends BaseStandaloneForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    protected $id = 'settings';
    protected $name = 'settings';

    public function initContent()
    {

        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->initIds('settingsForm');
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\Settings());
        $this->loadDataToForm();
        $data=$this->dataProvider->getData();
        $this->tabAdminSetting();
        $this->tabServerSetting($data);
        $this->tabSecuritySetting();
        $this->tabEmailSetting();
        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId($this->id);
        $submitButton->runInitContentProcess();
        $this->setSubmit($submitButton);
        $this->loadDataToForm();
    }
    private function tabAdminSetting()
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName('adminsettings');
        $settings = (new Sections\BoxSection())->setMainContainer($this->mainContainer);



        $field = (new Fields\Switcher('service_email_active'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('oversell'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('suspend'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('user_backup'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('auto_update'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('demo_user'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('demo_reseller'))->addClass('w-150');
        $settings->addField($field);
        $field = (new Fields\Switcher('demo_admin'))->addClass('w-150');
        $settings->addField($field);

        $field = (new Fields\Text('backup_threshold'))->addClass('w-150');

        $settings->addField($field);

        $section->addSection($settings);
        $this->addSection($section);
    }
    private function tabServerSetting($data)
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName('serversettings');
        $settings = (new Sections\BoxSection())->setMainContainer($this->mainContainer);

        $field = (new Fields\Text('servername'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('ns1'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('ns2'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('timeout'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('session_minutes'))->addClass('w-50');
        $settings->addField($field);
        $field = (new MaxUploadsize('maxfilesize'))->setUnit($data['maxfilesize_units']->unit)->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('logs_to_keep'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('demodocsroot'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('partition_usage_threshold'))->addClass('w-50')->setSuffixText('percentusage');
        $settings->addField($field);

        $field = (new Fields\Select('check_partitions'))->addClass('w-50');
        $field->setAvailableValues([
            '1'=>'Minute',
            '2'=>'Day',
            '0'=>'Never'
        ]);
        $settings->addField($field);

        $field = (new Fields\Select('timezone'))->addClass('w-50');
        // $OptionsArray = timezone_identifiers_list();

        // $field->setAvailableValues($OptionsArray);
        $settings->addField($field);

        $field = (new Fields\Text('max_username_length'))->addClass('w-50');
        $settings->addField($field);

        $section->addSection($settings);
        $this->addSection($section);
    }
    private function tabSecuritySetting()
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName('securitysettings');
        $settings = (new Sections\BoxSection())->setMainContainer($this->mainContainer);

        $field = (new Fields\Switcher('lost_password'));
        $settings->addField($field);
        $field = (new Fields\Switcher('bruteforce'));
        $settings->addField($field);
        $field = (new Fields\Text('brutecount'))->addClass('w-50')->setPrefixText('after')->setSuffixText('loginattempts');
        $settings->addField($field);
        $field = (new Fields\Text('brute_dos_count'))->addClass('w-50')->setPrefixText('or')->setSuffixText('unauthconn');;
        $settings->addField($field);
        $field = (new Fields\Switcher('exempt_local_block'));
        $settings->addField($field);
        $field = (new Fields\Text('brute_force_time_limit'))->addClass('w-50')->setSuffixText('brute_force_time_limitsuffix');
        $settings->addField($field);
        $field = (new Fields\Text('clear_blacklist_ip_time'))->addClass('w-50')->setSuffixText('clear_blacklist_ip_timesuffix');
        $settings->addField($field);
        $field = (new Fields\Switcher('brute_force_log_scanner'));
        $settings->addField($field);
        $field = (new Fields\Text('ip_brutecount'))->addClass('w-50')->setSuffixText('ip_brutecountsuffix');
        $settings->addField($field);

        $field = (new Fields\Text('user_brutecount'))->addClass('w-50')->setSuffixText('user_brutecountsuffix');
        $settings->addField($field);

        $field = (new Fields\Text('unblock_brute_ip_time'))->addClass('w-50')->setSuffixText('unblock_brute_ip_time_suffix');
        $settings->addField($field);

        $field = (new Fields\Text('clear_brute_log_time'))->addClass('w-50')->setSuffixText('clear_brute_log_timesuffix');
        $settings->addField($field);


        $field = (new Fields\Text('clear_brute_log_entry_time'))->addClass('w-50')->setSuffixText('clear_brute_log_entry_timesuffix');
        $settings->addField($field);


        $field = (new Fields\Select('brute_force_scan_apache_logs'))->addClass('w-50');
        $field->setAvailableValues([
            '2'=>'All Logs',
            '1'=>'Manual',
            '0'=>'No'
        ]);
        $settings->addField($field);

        $field = (new Fields\Switcher('enforce_difficult_passwords'));
        $settings->addField($field);

        $field = (new Fields\Switcher('check_subdomain_owner'));
        $settings->addField($field);


        $section->addSection($settings);
        $this->addSection($section);
    }
    private function tabEmailSetting()
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName('emailsettings');
        $settings = (new Sections\BoxSection())->setMainContainer($this->mainContainer);

        $field = (new Fields\Text('virtual_limit'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Text('per_email_limit'))->addClass('w-50');
        $settings->addField($field);
        $field = (new Fields\Switcher('user_can_set_email_limit'));
        $settings->addField($field);
        $field = (new Fields\Text('max_per_email_send_limit'))->addClass('w-50')->setSuffixText('max_per_email_send_limitsuffix');

        $settings->addField($field);
        $field = (new Fields\Switcher('rbl_enabled'));
        $settings->addField($field);
        $field = (new Fields\Text('purge_spam_days'))->addClass('w-50');
        $settings->addField($field);

        $section->addSection($settings);
        $this->addSection($section);
    }
}
