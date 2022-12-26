<?php

namespace ModulesGarden\Servers\AwsEc2\App\Helpers;

use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\EmailTemplate;

class EmailSender
{
    public function send($templateId, $serviceId, $customVars)
    {
        $templateRepo = new EmailTemplate();
        unset($customVars['LaunchTime']);
        unset($customVars['NetworkInterfaces_0_Attachment_AttachTime']);
        $template = $templateRepo->find($templateId);
        $command = 'SendEmail';
        $postData = array(
            'messagename' => $template->name,
            'id' => $serviceId,
            'customtype' => $template->type,
            'customsubject' => $template->subject,
            'custommessage' => $template->message,
            'customvars' => base64_encode(serialize($customVars))
        );
        logModuleCall("AWSEc2","EmailSender",$postData,$customVars);
        return localAPI($command, $postData);
    }

}
