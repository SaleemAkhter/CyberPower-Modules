<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\AppLayoutConstants;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\TemplateConstants;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;

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
        return \ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR
        . (Helper\isAdmin() ? TemplateConstants::ADMIN_PATH : TemplateConstants::CLIENT_PATH . DIRECTORY_SEPARATOR) .
        TemplateConstants::MAIN_DIR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR .'default' . DIRECTORY_SEPARATOR . 'appLayouts';
    }
}
