<?php

namespace ModulesGarden\Servers\VultrVps\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\VultrVps\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\VultrVps\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\VultrVps\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
