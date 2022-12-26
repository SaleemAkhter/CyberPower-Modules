<?php

namespace ModulesGarden\WordpressManager\Core\App\Controllers\Instances;

use ModulesGarden\WordpressManager\Core\App\Controllers\Http\PageNotFound;
use \ModulesGarden\WordpressManager\Core\App\Controllers\Interfaces\DefaultController;

abstract class AddonController implements DefaultController
{
    use \ModulesGarden\WordpressManager\Core\Traits\Lang;
    use \ModulesGarden\WordpressManager\Core\Traits\OutputBuffer;
    use \ModulesGarden\WordpressManager\Core\Traits\IsAdmin;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\WordpressManager\Core\Traits\ErrorCodesLibrary;
}
