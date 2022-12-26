<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping;

use Exception;

class TestConnection extends Abstracts\AbstractImapConnection
{

    public function check()
    {
        $this->setImap();
        return true;
    }

}
