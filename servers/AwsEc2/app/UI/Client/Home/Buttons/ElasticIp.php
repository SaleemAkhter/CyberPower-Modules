<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\GetKeyModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonRedirect;
class ElasticIp extends ButtonRedirect
{
    protected $id             = 'elasticIpButton';
    protected $name           = 'elasticIpButton';
    protected $class          = ['lu-btn lu-btn--success submitForm mg-submit-form'];
    protected $icon           = '';
    protected $title          = 'title';
    protected $showTitle      = true;
    protected $htmlAttributes = [];

}
