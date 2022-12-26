<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 */
class EmailTemplate
{

    /**
     * Get email tempaltes file
     * Add custom template 
     */
    public function create()
    {
        $templates = $this->getTemplates();
        $this->activateTempates($templates['emails']);

    }

    /**
     * Check is email template exists
     * 
     * @param string $templateName
     * @return object insteandof EmailTemplates
     */
    private function checkEmailTemplate($templateName)
    {
        return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\EmailTemplate::where('name', $templateName)->first();
    }

    private function getPath()
    {
        return ModuleConstants::getModuleRootDir() . DS . 'app' . DS . 'Config' . DS . 'emailTemplates.json';
    }

    /**
     * Get and read JSON file
     * 
     * @return JSON object
     */
    private function getTemplates()
    {
        $file = Reader::read($this->getPath());
        return $file->get();
    }

    /**
     * Add new email tempalte
     * 
     * @throw database error
     */
    private function activateTempates($templates)
    {

        foreach ($templates as $template)
        {
            if (!$this->checkEmailTemplate($template['name']))
            {

                $emailTemplate          = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\EmailTemplate();
                $emailTemplate->type    = $template['type'];
                $emailTemplate->custom  = $template['custom'];
                $emailTemplate->name    = $template['name'];
                $emailTemplate->subject = $template['subject'];
                $emailTemplate->message = $template['message'];
                $emailTemplate->save();
            }
        }
    }

}
