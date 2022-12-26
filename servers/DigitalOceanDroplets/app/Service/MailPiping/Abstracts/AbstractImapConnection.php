<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping\Abstracts;

use Exception;

/**
 * Description of abstractImapConnection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
abstract class AbstractImapConnection
{

    protected $imapConfig;
    protected $imap;

    public function __construct($config)
    {
        $this->setGlobalConfig();
        $this->setImapConfig($config);
    }

    private function setImapConfig($config)
    {
        $this->imapConfig = $config;
    }

    private function setGlobalConfig()
    {
        global $CONFIG;
        $this->globalConfig = $CONFIG;
    }

    private function prepareImapString()
    {
        $imapString = $this->imapConfig->hostname . ":" . $this->imapConfig->port . '/imap/' . strtolower($this->imapConfig->sslType);

        if(isset($this->imapConfig->noValidateCertificate) && $this->imapConfig->noValidateCertificate == "on"){
            $imapString .= '/novalidate-cert';
        }

        return "{" . $imapString . "}".$this->imapConfig->folder;
    }

    private function checkImapExist()
    {
        if (!function_exists('imap_open'))
        {
            throw new Exception("IMAP needs to be compiled into PHP for this to function.");
        }
    }

    protected function setImap()
    {
        $this->checkImapExist();
        imap_timeout(IMAP_OPENTIMEOUT, 10);

        $mbox = imap_open($this->prepareImapString(), $this->imapConfig->username, html_entity_decode($this->imapConfig->password));
        if ($mbox === false)
        {
            throw new Exception(imap_last_error());
        }
        $this->imap = $mbox;
    }

}
