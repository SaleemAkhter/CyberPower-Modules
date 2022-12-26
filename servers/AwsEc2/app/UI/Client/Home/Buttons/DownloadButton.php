<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\AwsEc2\App\Models\SSHKey\SSHKeysRepository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonRedirect;

class DownloadButton extends ButtonRedirect
{
    protected $id             = 'downloadButton';
    protected $name           = 'downloadButton';
    protected $class          = ['lu-btn lu-btn--success submitForm mg-submit-form'];
    protected $icon           = '';
    protected $title          = 'title';
    protected $showTitle      = true;
    protected $htmlAttributes = [];

}