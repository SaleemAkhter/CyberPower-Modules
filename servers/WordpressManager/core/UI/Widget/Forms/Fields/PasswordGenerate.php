<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

class PasswordGenerate extends BaseField
{
    protected $id           = 'passwordGenerate';
    protected $name         = 'passwordGenerate';
    
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-pass-gen';    
}
