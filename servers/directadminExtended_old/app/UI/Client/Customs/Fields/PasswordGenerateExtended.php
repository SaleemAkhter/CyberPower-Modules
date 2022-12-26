<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

class PasswordGenerateExtended extends BaseField
{
    protected $id           = 'passwordGenerateExtended';
    protected $name         = 'passwordGenerateExtended';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-pass-gen-ext';



}
