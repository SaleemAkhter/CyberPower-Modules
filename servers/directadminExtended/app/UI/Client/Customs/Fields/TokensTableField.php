<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

class TokensTableField extends BaseField
{
    protected $id           = 'tokensTableField';
    protected $name         = 'tokensTableField';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-tokens-table';
    protected $tokens=[];

    public function setTokens($tokens)
    {
        $this->tokens=$tokens;
        return $this;
    }

    public function getTokens()
    {
        return json_encode($this->tokens);
    }

}
