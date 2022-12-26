<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Helpers;

use ModulesGarden\OvhVpsAndDedicated\Core\FileReader\Reader;
use ModulesGarden\OvhVpsAndDedicated\Core\ModuleConstants;

/**
 * Description of ConfigOptions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
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
        return \ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\EmailTemplate::where('name', $templateName)->first();
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

                $emailTemplate          = new \ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\EmailTemplate();
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
