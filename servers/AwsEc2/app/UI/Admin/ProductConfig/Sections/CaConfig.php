<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Sections;


use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Sections\BoxSection;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Switcher;

class CaConfig extends BoxSection implements AdminArea
{
    protected $id = 'caConfig';
    protected $name = 'caConfig';
    protected $title = 'caConfigTitle';

    public function initContent()
    {

        $windowsPassword = new Switcher('getWindowsPassword');
        $windowsPassword->addGroupName('mgpci');
        $windowsPassword->setDescription('getWindowsPasswordDescription');
        $this->addField($windowsPassword);

        $hideDnsName = new Switcher('hideDnsName');
        $hideDnsName->addGroupName('mgpci');
        $hideDnsName->setDescription('hideDnsNameDescription');
        $this->addField($hideDnsName);

        $hideScheduledTasks = new Switcher('hideScheduledTasks');
        $hideScheduledTasks->addGroupName('mgpci');
        $hideScheduledTasks->setDescription('hideScheduledTasksDescription');
        $this->addField($hideScheduledTasks);

        $hideIpv6 = new Switcher('hideIpv6');
        $hideIpv6->addGroupName('mgpci');
        $hideIpv6->setDescription('hideIpv6Description');
        $this->addField($hideIpv6);

        $enableFirewallConfig = new Switcher('enableFirewallConfig');
        $enableFirewallConfig->addGroupName('mgpci');
        $enableFirewallConfig->setDescription('enableFirewallConfigDescription');
        $this->addField($enableFirewallConfig);

    }
}