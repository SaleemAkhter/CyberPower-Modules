<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\RequestObjectHandler;

    public function __construct()
    {
        $this->loadRequestObj();
    }
}
