<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\AwsEc2\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\AwsEc2\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\AwsEc2\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
