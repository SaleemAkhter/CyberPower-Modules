<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class TextHorizontal extends BaseField
{
    protected $id   = 'textHorizontal';
    protected $name = 'textHorizontal';
    protected $passwordfield=false;
    function isPassword(){
        return $this->passwordfield;
    }
    function setIsPassword($password){
        $this->passwordfield=$password;
        return $this;
    }
}
