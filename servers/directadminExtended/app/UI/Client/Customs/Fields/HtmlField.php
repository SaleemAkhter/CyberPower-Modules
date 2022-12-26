<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class HtmlField extends BaseField
{
    protected $id   = 'htmlField';
    protected $name = 'htmlField';
    protected $rawHtml='';
    public function setRawHtml($html)
    {
         $this->rawHtml=$html;
         return $this;
    }
    public function getRawHtml()
    {
        return $this->rawHtml;
    }
}
