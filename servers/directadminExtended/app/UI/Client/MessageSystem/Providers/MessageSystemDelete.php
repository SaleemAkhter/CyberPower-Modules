<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Providers;


class MessageSystemDelete extends MessageSystem
{

    public function read()
    {
        $this->data['messagesystem'] = $this->actionElementId;
    }
}
