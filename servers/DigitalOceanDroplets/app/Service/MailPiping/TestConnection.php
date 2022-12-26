<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping;

use Exception;

class TestConnection extends Abstracts\AbstractImapConnection
{

    public function check()
    {
        $this->setImap();
        return true;
    }

}
