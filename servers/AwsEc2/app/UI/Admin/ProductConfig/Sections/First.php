<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Sections;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields\Amis;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields\Region;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields\Subnets;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Sections\SectionLuRow;


class First extends BoxSection implements AdminArea
{
    protected $id = 'region';
    protected $name = 'region';
    protected $title = 'regionTitle';

    public function initContent()
    {
        $regions = new Region('region');
        $regions->addGroupName('mgpci');
        $regions->setDescription('regionsDescription');
        $this->addField($regions);

        $ami = new Amis('ami');
        $ami->addGroupName('mgpci');
        $ami->setDescription('amiDescription');
        $this->addField($ami);

        $instanceType = new Select('instanceType');
        $instanceType->addGroupName('mgpci');
        $instanceType->setDescription('instanceTypeDescription');
        $this->addField($instanceType);

        $firewall = new Switcher('firewall');
        $firewall->setDescription('firewallDescription');
        $firewall->addGroupName('mgpci');
        $this->addField($firewall);

        $securityGroups = (new Select('securityGroups'))->setName('securityGroups[]')->enableMultiple();
        $securityGroups->addGroupName('mgpci');
        $securityGroups->setDescription('securityGroupsDescription');
        $this->addField($securityGroups);

        $numberOfFirewallRules = new Number('numberOfFirewallRules');
        $numberOfFirewallRules->addGroupName('mgpci');
        $numberOfFirewallRules->addHtmlAttribute('min', '-1');
        $numberOfFirewallRules->setDescription('numberOfFirewallRulesDescription');
        $this->addField($numberOfFirewallRules);

        $ipv4 = new Number('ipv4');
        $ipv4->addGroupName('mgpci');
        $ipv4->setDefaultValue(0);
        $ipv4->addHtmlAttribute('min', '0');
        $ipv4->setDescription('ipv4Description');
        $this->addField($ipv4);

        $tagName = new Text('tagName');
        $tagName->setPlaceholder('WHMCS');
        $tagName->setDescription('tagNameDescription');
        $tagName->addGroupName('mgpci');
        $this->addField($tagName);

        $capacityReservationSpecification = new Switcher('capacityReservationSpecification');
        $capacityReservationSpecification->addGroupName('mgpci');
        $capacityReservationSpecification->setDescription('capacityReservationDescription');
        $this->addField($capacityReservationSpecification);

        $hibernationOptions = new Switcher('hibernationOptions');
        $hibernationOptions->addGroupName('mgpci');
        $hibernationOptions->setDescription('hibernationOptionsDescription');
        $this->addField($hibernationOptions);

        $logApiRequests = new Switcher('logApiRequests');
        $logApiRequests->addGroupName('mgpci');
        $logApiRequests->setDescription('logApiRequestsDescription');
        $this->addField($logApiRequests);

        $userData = new Textarea('userData');
        $userData->setPlaceholder('#!/bin/bash
mkdir exampleDirectory
touch exampleDirectory/exampleFile.txt');
        $userData->setDescription('userDataDescription');
        $userData->addGroupName('mgpci');

        $userDataFile = new Select('userDataFile');
        $userDataFile->setDescription('userDataFileDescription');
        $userDataFile->addGroupName('mgpci');

        $diskSize = new Text('volumeSize');
        $diskSize->setDescription('volumeSizeDescription');
        $diskSize->addGroupName('mgpci');

        $volumeType = new Select('volumeType');
        $volumeType->setDescription('volumeTypeDescription');
        $volumeType->addGroupName('mgpci');

        $iops = new Number('iops', 100, 200);
        $iops->addHtmlAttribute('min', '100');
        $iops->setDefaultValue(100);
        $iops->setDescription('iopsDescription');
        $iops->addGroupName('mgpci');


        $emailTemplate = new Select('emailTemplate');
        $emailTemplate->addGroupName('mgpci');
        $emailTemplate->setDescription('emailTemplateDescription');

        $subnet = new Subnets('subnet');
        $subnet->setDescription('subnetDescription');
        $subnet->addGroupName('mgpci');
        $subnet->addReloadOnChangeField('region');
//        $ipv6 = new Number('ipv6');
//        $ipv6->addGroupName('mgpci');
//        $ipv6->setDefaultValue(0);
//        $ipv6->addHtmlAttribute('min', '0');
//        $ipv6->setDescription('ipv6Description');

        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $hps = new HalfPageSection('hps');
        $hps->setMainContainer($this->mainContainer);
//        $hps->addField($ipv6);
        $hps->addField($userData);
        $hps->addField($userDataFile);
        $hps->addField($subnet);
        $hps->addField($diskSize);
        $hps->addField($volumeType);
        $hps->addField($iops);
        $hps->addField($emailTemplate);

        $row->addSection($hps);
        $this->addSection($hps);
    }
}
