<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Accountleft extends BaseSection
{
    protected $id   = 'accountleftSection';
    protected $name = 'accountleftSection';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

    }

}
