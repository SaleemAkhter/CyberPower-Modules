<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
