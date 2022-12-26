<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Traits;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\AppLayoutConstants;
use \ModulesGarden\Servers\AwsEc2\Core\UI\Helpers\TemplateConstants;
use \ModulesGarden\Servers\AwsEc2\Core\Helper;

/**
 * App Layouts related functions
 * View Trat
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AppLayouts
{
    protected $appLayout = AppLayoutConstants::NAVBAR_TOP;
    
    public function getAppLayout()
    {
        return $this->appLayout;
    }
    
    public function setAppLayout($layout)
    {
        if (in_array($layout, [
            AppLayoutConstants::NAVBAR_LEFT, 
            AppLayoutConstants::NAVBAR_LEFT_THIN,
            AppLayoutConstants::NAVBAR_RIGHT,
            AppLayoutConstants::NAVBAR_RIGHT_THIN,
            AppLayoutConstants::NAVBAR_TOP
        ]))
        {
            $this->appLayout = $layout;
        }
        
        return $this;
    }
    
    public function getAppLayoutTemplateDir()
    {
        return \ModulesGarden\Servers\AwsEc2\Core\ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR
        . (Helper\isAdmin() ? TemplateConstants::ADMIN_PATH : TemplateConstants::CLIENT_PATH . DIRECTORY_SEPARATOR) .
        TemplateConstants::MAIN_DIR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR .'default' . DIRECTORY_SEPARATOR . 'appLayouts';
    }
}
