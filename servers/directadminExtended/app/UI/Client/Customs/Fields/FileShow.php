<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class FileShow extends BaseField
{
    protected $id   = 'fileShowlField';
    protected $name = 'fileShowlField';
    protected $lines=[];
    public function setLines($lines)
    {
         $this->lines=$lines;
         return $this;
    }
    public function getLines()
    {
        return $this->lines;
    }
}
