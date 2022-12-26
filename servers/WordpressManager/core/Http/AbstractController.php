<?php

namespace ModulesGarden\WordpressManager\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\WordpressManager\Core\Traits\OutputBuffer;
    use \ModulesGarden\WordpressManager\Core\Traits\IsAdmin;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
