<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
