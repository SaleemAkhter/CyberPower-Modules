<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping\Traits;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\MailBoxRead;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting;

/**
 * Description of ImapReader
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
trait ContentParser
{

    private function readMails()
    {
        if (is_array($this->imapHeader) === false)
        {
            return $this;
        }

        foreach ($this->imapHeader as $mail)
        {

            if (!empty($this->checkEmailExistOnSystem($mail)))
            {
                continue;
            }
            try
            {
                $hostingID = $this->assignMailToAccount($mail);
                $this->saveDetailsInDB($mail, 'success', 'Email has been forwarded.', $hostingID);
            }
            catch (Exception $ex)
            {
                $this->saveDetailsInDB($mail, 'error', $ex->getMessage());
            }
        }

        return $this;
    }

    private function checkEmailExistOnSystem($mailID)
    {
        return MailBoxRead::whereMailID($mailID)->whereMail($this->imapConfig->username)->first();
    }

    private function saveDetailsInDB($mailID, $status, $msg, $hostingID = 0)
    {
        $mailSaved             = new MailBoxRead();
        $mailSaved->mail_id    = $mailID;
        $mailSaved->status     = $status;
        $mailSaved->message    = $msg;
        $mailSaved->hosting_id = $hostingID;
        $mailSaved->mail = $this->imapConfig->username;
        $mailSaved->save();
        echo ucfirst($status) . ": " . $msg . "\n";
    }

    private function getFieldsConfigurationFromBodyAndSave(Hosting $service, $mailBody)
    {

        $body = strip_tags($mailBody);
        preg_match_all("/[a-zA-Z ]+[:](.*)+/", $body, $output_array);

        $configArray = [];

        foreach ($output_array[0] as $item)
        {
            $conf = explode(":", $item);
            if (count($conf) > 2)
            {
                continue;
            }

            $configArray[strtolower(str_replace(' ', '', trim($conf[0])))] = str_replace(' ', '', trim($conf[1]));
        }

        if (!empty($configArray))
        {
            $this->checkFieldsToUpdate($service, $configArray);
        }
    }

    private function checkFieldsToUpdate(Hosting $service, $configArray)
    {
        if (isset($configArray['spasswordhasbeenresetto']))
        {
            $this->updateAccountInformation($service, ['password' => \encrypt($configArray['spasswordhasbeenresetto'])]);
            $this->sendPasswordResetEmail($service, $configArray['spasswordhasbeenresetto']);
        }
        elseif (isset($configArray['ipaddress']) || isset($configArray['username']) || isset($configArray['password']))
        {

            $config = [];
            if (!empty($configArray['ipaddress']))
            {
                $config['dedicatedip'] = $configArray['ipaddress'];
            }
            if (!empty($configArray['username']))
            {
                $config['username'] = $configArray['username'];
            } if (!empty($configArray['password']))
            {
                $config['password'] = \encrypt($configArray['password']);
            }
            if (!empty($config))
            {
                $this->updateAccountInformation($service, $config);
                $this->sendCreateEmail($service, \decrypt($config['password']), $config['username']);
            }
        }
    }

    private function assignMailToAccount($mail)
    {

        $mailBody    = $this->readBody($mail);
        $mailHeader  = $this->readHederInfo($mail);
        $dropletName = $this->getDropletNameFromHeader($mailHeader);
        $service     = $this->findServiceForDomain($dropletName);
        $config      = $this->getFieldsConfigurationFromBodyAndSave($service, $mailBody);
        return $service->id;
        throw new Exception('Service not found');
    }

    private function getDropletNameFromHeader($mailHeader)
    {

        if (!empty($mailHeader->subject))
        {
            $dopletName = $this->findDropletName($mailHeader->subject);
            if (empty($dopletName))
            {
                throw new Exception('The message not from DigitalOceanDroplets');
            }
            return $dopletName;
        }
        throw new Exception('Message with a empty subject');
    }

    private function findDropletName($subject)
    {
        preg_match("/([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,10}/", $subject, $output);

        if (!empty($output[0]))
        {
            return $output[0];
        }
        
        preg_match("/[:](.*)/", $subject, $output);

        if (!empty($output[1]))
        {
            return ltrim($output[1]);
        }
        
        preg_match("/[(](.*)[)]/", $subject, $output);
        
        if (!empty($output[1]))
        {
            return $output[1];
        }
    }

    private function updateAccountInformation(Hosting $service, $config)
    {
        if (is_array($config))
        {
            $service->update($config);
            $service->save();
        }
    }

    private function findServiceForDomain($dropletName)
    {
        $service = Hosting::where('domain', $dropletName)->with('product')->whereHas('product', function($q)
                {
                    $q->where('servertype', 'DigitalOceanDroplets');
                })->first();
        if (!is_null($service))
        {
            return $service;
        }
        throw new \Exception('Service not found. Droplet name: ' . $dropletName);
    }

    private function sendCreateEmail(Hosting $service, $password, $user = "root")
    {
        $config   = $this->getConfig($service->packageid);
        $postData = array(
            'id'          => $service->id,
            'messagename' => $config->getField('createEmailTemplate', 'Digital Ocean Create Email'),
            'customvars'  => base64_encode(serialize(["droplet_name" => $service->domain, "droplet_user" => $user, "droplet_password" => $password])),
        );
        $result   = localAPI('SendEmail', $postData);
        if ($result['result'] != "success")
        {
            throw new Exception('Email cannot be sent. Hositng ID #' . $service->id);
        }
    }

    private function sendPasswordResetEmail(Hosting $service, $password)
    {
        $config   = $this->getConfig($service->packageid);
        $postData = array(
            'id'          => $service->id,
            'messagename' => $config->getField('passwordEmailTemplate', 'Digital Ocean Password Reset Email'),
            'customvars'  => base64_encode(serialize(["droplet_name" => $service->domain, "droplet_password" => $password])),
        );

        $result = localAPI('SendEmail', $postData);
        if ($result['result'] != "success")
        {
            throw new Exception('Email cannot be sent. Hositng ID #' . $service->id);
        }
    }

    private function getConfig($id)
    {
        return new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider($id);
    }

}
