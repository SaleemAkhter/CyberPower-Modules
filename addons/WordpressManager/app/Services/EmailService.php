<?php


namespace ModulesGarden\WordpressManager\App\Services;


use ModulesGarden\WordpressManager\Core\Api\Whmcs;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\Admins;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\EmailTemplate;

class EmailService
{
    private $api;
    private $templateId;
    private $vars = [];
    private $lastResponse;

    /**
     * EmailService constructor.
     * @param $api
     */
    public function __construct()
    {
        $this->api = new Whmcs(new Admins());
    }

    public function template($templateId)
    {
        if (!$templateId)
        {
            throw new \InvalidArgumentException("Email Template Id cannot be empty");
        }
        $this->templateId          = $templateId;
        $template =  EmailTemplate::select("name", "type")->where("id", $this->templateId)->first();
        if(!$template){
            throw new \InvalidArgumentException(sprintf("Email Template with Id: #%s does not exist",$templateId));
        }
        $this->vars                = $template->toArray();
        $this->vars['messagename'] = $this->vars['name'];
        unset($this->vars['name']);
        return $this;
    }

    public function vars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
        return $this;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function send()
    {
        $this->lastResponse = $this->api->call("sendemail", $this->vars);
        $this->vars         = [];
        $this->templateId   = null;
        return $this;
    }

    public function sendToAdmin()
    {
        if (function_exists('sendAdminMessage'))
        {
            /**
             * sendAdminMessage ($templateName, $adminId = "", $templateVars = array(), $to = "system", $deptid = "", $ticketNotify = "")
             */
            sendAdminMessage($this->vars['messagename'], $this->vars);
            $this->vars       = [];
            $this->templateId = null;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }


}