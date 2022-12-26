<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class MaxUploadsize extends BaseField
{
    protected $id   = 'maxUploadsize';
    protected $name = 'maxUploadsize';

    public function setUnit($unit)
    {
         $this->unit=$unit;
         return $this;
    }
    public function getUnit()
    {
         return $this->unit;
    }
}
