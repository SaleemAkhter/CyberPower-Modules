<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\OutputBuffer;
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\IsAdmin;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
