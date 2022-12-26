<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\ProductConfiguration\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\SectionLuRow;

/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 01.04.2019
 * Time: 19:24
 */

class ConfigurationFields extends BoxSection implements AdminArea
{
    protected $id       = 'configurationFields';
    protected $name     = 'configurationFields';
    protected $title    = 'configurationFields';

    public function initContent()
    {
        $this->addSection($this->getLeftSection())
            ->addSection($this->getRightSection());
    }

    public function getLeftSection()
    {
        $leftSection        = new HalfPageSection('leftSection');
        $leftSection->setMainContainer($this->mainContainer);
        $package            = (new Fields\Select('package'))
            ->addGroupName('mgpci')
            ->setDescription('packageDescription')
            ->addHtmlAttribute('onchange', 'productConfigurationSelect(event)');
        $dedicatedIP        = (new Fields\Switcher('dedicated_ip'))
            ->addGroupName('mgpci')
            ->setDescription('dedicated_ip_description');
        $bandwidth          = (new Fields\Text('bandwidth'))
            ->addGroupName('mgpci')
            ->setDescription('bandwidthDescription');
        $ftpAccounts        = (new Fields\Text('ftp'))
            ->addGroupName('mgpci')
            ->setDescription('ftpDescription');
        $mysqlDatabases     = (new Fields\Text('mysql'))
            ->addGroupName('mgpci')
            ->setDescription('mysqlDescription');
        $domainPointers     = (new Fields\Text('domainptr'))
            ->addGroupName('mgpci')
            ->setDescription('domainPointers');
        $emailForwarders    = (new Fields\Text('nemailf'))
            ->addGroupName('mgpci')
            ->setDescription('emailForwardersDescrption');
        $autoResponders     = (new Fields\Text('nemailr'))
            ->addGroupName('mgpci')
            ->setDescription('autorespondersDescription');
        $cgi                = (new Fields\Switcher('cgi'))
            ->addGroupName('mgpci')
            ->setDescription('cgiDescrpition');
        $php                = (new Fields\Switcher('php'))
            ->addGroupName('mgpci')
            ->setDescription('phpDescription');
        $systemInfo         = (new Fields\Switcher('sysinfo'))
            ->addGroupName('mgpci')
            ->setDescription('sysinfoDescription');
        $dnsControl         = (new Fields\Switcher('dnscontrol'))
            ->addGroupName('mgpci')
            ->setDescription('dnsControlDescription');
        $anonymousFTP       = (new Fields\Switcher('aftp'))
            ->addGroupName('mgpci')
            ->setDescription('anonymousFtpDescription');
        $hotlinkProtection = (new Fields\Switcher('hotlink_protection'))
            ->addGroupName('mgpci')
            ->setDescription('hotlinkDescription');


        $leftSection->addField($package)
            ->addField($dedicatedIP)
            ->addField($bandwidth)
            ->addField($ftpAccounts)
            ->addField($mysqlDatabases)
            ->addField($domainPointers)
            ->addField($emailForwarders)
            ->addField($autoResponders)
            ->addField($cgi)
            ->addField($php)
            ->addField($systemInfo)
            ->addField($dnsControl)
            ->addField($anonymousFTP);
            //->addField($hotlinkProtection);

        return $leftSection;
    }

    public function getRightSection()
    {
        $leftSection        = new HalfPageSection('rightSection');
        $leftSection->setMainContainer($this->mainContainer);
        $resellerIP         = (new Fields\Select('reseller_ip'))
            ->addGroupName('mgpci')
            ->setDescription('resellerIpDescription');
        $suspentAtLimit     = (new Fields\Switcher('suspend_at_limit'))
            ->addGroupName('mgpci')
            ->setDescription('suspendLimitDescription');
        $quota              = (new Fields\Text('quota'))
            ->addGroupName('mgpci')
            ->setDescription('quotaDescription');
        $emailAccounts      = (new Fields\Text('nemails'))
            ->addGroupName('mgpci')
            ->setDescription('emailsDescription');
        $subdomains         = (new Fields\Text('nsubdomains'))
            ->addGroupName('mgpci')
            ->setDescription('subdomainsDescription');
        $addonDomains       = (new Fields\Text('vdomains'))
            ->addGroupName('mgpci')
            ->setDescription('addondomainsDescription');
        $mailingLists       = (new Fields\Text('nemailml'))
            ->addGroupName('mgpci')
            ->setDescription('mailinglistsDescription');
        $inode       = (new Fields\Text('inode'))
            ->addGroupName('mgpci')
            ->setDescription('inodeDescription');
        $spamAssasin        = (new Fields\Switcher('spam'))
            ->addGroupName('mgpci')
            ->setDescription('spamDescription');
        $ssh                = (new Fields\Switcher('ssh'))
            ->addGroupName('mgpci')
            ->setDescription('sshDescription');
        $ssl                = (new Fields\Switcher('ssl'))
            ->addGroupName('mgpci')
            ->setDescription('sslDescription');
        $catchEmails        = (new Fields\Switcher('catchall'))
            ->addGroupName('mgpci')
            ->setDescription('catchallDescription');
        $cron               = (new Fields\Switcher('cron'))
            ->addGroupName('mgpci')
            ->setDescription('cronDescription');
        $protectedDirectories = (new Fields\Switcher('protected_directories'))
            ->addGroupName('mgpci')
            ->setDescription('protectedDirectories');

        $leftSection->addField($resellerIP)
            ->addField($suspentAtLimit)
            ->addField($quota)
            ->addField($emailAccounts)
            ->addField($subdomains)
            ->addField($addonDomains)
            ->addField($mailingLists)
            ->addField($inode)
            ->addField($spamAssasin)
            ->addField($ssh)
            ->addField($ssl)
            ->addField($catchEmails)
            ->addField($cron);
           // ->addField($protectedDirectories);

        return $leftSection;
    }

}
