<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping\Abstracts;

use Exception;

/**
 * Description of abstractImapConnection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
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
        $sslType = strtoupper($this->imapConfig->sslType) === 'NONE' ? '' : '/'.$this->imapConfig->sslType; 
        return "{" . $this->imapConfig->hostname . ":" . $this->imapConfig->port . $sslType . "}".$this->imapConfig->folder;
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
        if(!$this->imapConfig->hostname){
            throw new Exception("The Incoming Mail Configuration is invalid.");
        }
        imap_timeout(IMAP_OPENTIMEOUT, 10);
        $mbox = imap_open($this->prepareImapString(), $this->imapConfig->username, html_entity_decode($this->imapConfig->password));
        if ($mbox === false)
        {
            throw new Exception(imap_last_error());
        }
        $this->imap = $mbox;
    }

}
