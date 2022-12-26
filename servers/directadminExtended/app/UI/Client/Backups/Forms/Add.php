<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms\Sections\CustomBaseSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\Backups());

        $domain         = (new Fields\Switcher('domain'))->setDescription('description')->setValue("on");
        $forwarder      = (new Fields\Switcher('forwarder'))->setDescription('description')->setValue("on");
        $list           = (new Fields\Switcher('list'))->setDescription('description')->setValue('on');
        $ftpSettings    = (new Fields\Switcher('ftpSettings'))->setDescription('description')->setValue('on');
        $subdomain      = (new Fields\Switcher('subdomain'))->setDescription('description')->setValue('on');
        $autoresponder  = (new Fields\Switcher('autoresponder'))->setDescription('description')->setValue('on');
        $emailSettings  = (new Fields\Switcher('emailSettings'))->setDescription('description')->setValue('on');
        $emailData      = (new Fields\Switcher('emailData'))->setDescription('description')->setValue('on');
        $database       = (new Fields\Switcher('database'))->setDescription('description')->setValue('on');
        $email          = (new Fields\Switcher('email'))->setDescription('description')->setValue('on');
        $vacation       = (new Fields\Switcher('vacation'))->setDescription('description')->setValue('on');
        $ftp            = (new Fields\Switcher('ftp'))->setDescription('description')->setValue('on');
        $databaseData   = (new Fields\Switcher('databaseData'))->setDescription('description')->setValue('on');
        //$dns            = (new Fields\Switcher('dnsData'))->setDescription('description');


        $domainSection = new Sections\BaseSection('domainSection');
        $domainSection->addField($domain)
            ->addField($subdomain)
            ->unsetShowTitle();


        $emailSection = new Sections\BaseSection('emailSection');
        $emailSection->addField($autoresponder)
            ->addField($email)
            ->addField($emailData)
            ->addField($emailSettings)
            ->addField($forwarder)
            ->addField($vacation)
            ->addField($list)
            ->unsetShowTitle();

        $ftpSection = new Sections\BaseSection('ftpSection');
        $ftpSection->addField($ftp)
            ->addField($ftpSettings)
            ->unsetShowTitle();


        $dataBaseSection = new Sections\BaseSection('dataBaseSection');
        $dataBaseSection->addField($database)
            ->addField($databaseData)
            ->unsetShowTitle();
//
//        $dataBaseSection = new Sections\BaseSection('dnsBaseSection');
//        $dataBaseSection->addField($dns);




        $this->addSection($domainSection)
            ->addSection($emailSection)
            ->addSection($ftpSection)
            ->addSection($dataBaseSection);
    }
}
