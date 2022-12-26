<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
