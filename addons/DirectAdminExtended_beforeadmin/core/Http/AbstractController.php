<?php

namespace ModulesGarden\DirectAdminExtended\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\DirectAdminExtended\Core\Traits\OutputBuffer;
    use \ModulesGarden\DirectAdminExtended\Core\Traits\IsAdmin;
    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
