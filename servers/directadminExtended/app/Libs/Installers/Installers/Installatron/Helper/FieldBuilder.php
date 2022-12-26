<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Installatron\Helper;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\FieldTypeConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Generator;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Submodules\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

/**
 * Description of FieldBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class FieldBuilder
{
    use Lang;

    public function getBasicFields()
    {
        $fields = [];
        $this->generateBasicFields($fields);
        $this->generateDatabaseFields($fields);
        $this->generateAdminFields($fields);
        $this->generateAutoUpdateFields($fields);

        return $fields;
    }

    public function generateBasicFields(&$fields)
    {
        $this->loadLang();

        $dir = new Field();
        $dir->setName('directory')
                ->setLabel('Directory')
                ->setType(FieldTypeConstants::TEXT);

        $protocol = new Field();
        $protocol->setName('protocol')
            ->setLabel('Protocol')
            ->setType(FieldTypeConstants::SELECT);

        $content = new Field();
        $content->setName('content')
                ->setLabel('Content Install')
                ->setType(FieldTypeConstants::SELECT)
                ->setValue([ 'no' => $this->lang->absoluteT('cleanInstall'), 'yes' => $this->lang->absoluteT('demoContent')]);

        $langCodes = [
            'ar'          => 'Arabic',
            'ca'          => 'Catalan',
            'da'          => 'Dansk',
            'de'          => 'Deutsch',
            'de_formal'   => 'Deutsch (Sie-Form)',
            'en'          => 'English',
            'es'          => 'Español (Spanish)',
            'fr'          => 'Français',
            'gl'          => 'Gaelg',
            'he'          => 'Hebrew',
            'it'          => 'Italiano',
            'nl'          => 'Nederlands (Dutch)',
            'nl_informal' => 'Nederlands Informeel',
            'pl'          => 'Polski',
            'pt'          => 'Português (Portuguese)',
            'pt_br'       => 'Português do Brasil',
            'ro'          => 'Română',
            'ru'          => 'Russian',
            'gd'          => 'Scots Gaelic',
            'sl'          => 'Slovenščina (Slovenian)',
            'fi'          => 'Suomi',
            'sv'          => 'Swedish',
            'tr'          => 'Türkçe (Turkish)',
            'el'          => 'Ελληνικά (Greek)',
            'zh'          => '中文 (Chinese Simplified)',
            'zh_tw'       => '中文(台灣) (Chinese Traditional)'
        ];
        $lang = new Field();
        $lang->setName('language')
                ->setLabel('Language')
                ->setType(FieldTypeConstants::SELECT)
                ->setValue($langCodes);

        $fields[] = $dir;
        $fields[] = $protocol;
        $fields[] = $content;
        $fields[] = $lang;
    }

    /**
     * 
     * @param type $fields
     */
    public function generateDatabaseFields(&$fields)
    {
        $dbName = new Field();
        $dbName->setName('softdb')
                ->setLabel('Database Name')
                ->setType(FieldTypeConstants::TEXT);

        $dbUser = new Field();
        $dbUser->setName('db_user')
                ->setLabel('Database Username')
                ->setType(FieldTypeConstants::TEXT);

        $dbPass = new Field();
        $dbPass->setName('db_pass')
                ->setLabel('Database Password')
                ->setType(FieldTypeConstants::PASSWORD);

        $dbPrefix = new Field();
        $dbPrefix->setName('dbprefix')
                ->setLabel('Table Prefix')
                ->setType(FieldTypeConstants::TEXT);

        $fields[] = $dbName;
        $fields[] = $dbUser;
        $fields[] = $dbPass;
        $fields[] = $dbPrefix;
    }

    public function generateAdminFields(&$fields)
    {
        $siteName = new Field();
        $siteName->setName('sitetitle')
                ->setLabel('Site Name')
                ->setType(FieldTypeConstants::TEXT)
                ->setValue('My Site');

        $adminUsername = new Field();
        $adminUsername->setName('login')
                ->setLabel('Admin Username')
                ->setType(FieldTypeConstants::TEXT)
                ->setValue('admin');

        $adminPassword = new Field();
        $adminPassword->setName('passwd')
                ->setLabel('Admin Password')
                ->setType(FieldTypeConstants::PASSWORD)
                ->setValue(Generator::generate(new DirectAdmin()));

        $adminEmail = new Field();
        $adminEmail->setName('admin_email')
                ->setLabel('Admin Email')
                ->setType(FieldTypeConstants::TEXT);

        $fields[] = $siteName;
        $fields[] = $adminUsername;
        $fields[] = $adminPassword;
        $fields[] = $adminEmail;
    }

    public function generateAutoUpdateFields(&$fields)
    {
        $autoUpdate = new Field();
        $autoUpdate->setName('autoup')
                ->setLabel('Auto Update')
                ->setType(FieldTypeConstants::CHECKBOX);

        $autoUpdateBackup = new Field();
        $autoUpdateBackup->setName('autoup_backup')
                ->setLabel('Auto Update Backup')
                ->setType(FieldTypeConstants::CHECKBOX);

        $fields[] = $autoUpdate;
        $fields[] = $autoUpdateBackup;
    }
}
