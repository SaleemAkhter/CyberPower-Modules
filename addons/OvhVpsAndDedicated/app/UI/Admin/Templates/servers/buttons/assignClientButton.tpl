<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
{if $rawObject->isRawTitle()}title="{$rawObject->getRawTitle()}"{elseif $rawObject->getTitle()}title="{$MGLANG->T('button', $rawObject->getTitle())}"{/if}
class="{$rawObject->getClasses()}" {if $rawObject->isDisableByColumnValue()}{literal}:disabled="dataRow.{/literal}{$rawObject->getDisableColumnName()}{literal} != {/literal}{if $rawObject->getDisableByColumnValue() === null} null {/if}{if $rawObject->isDisableColumnValueString()}'{/if}{$rawObject->getDisableByColumnValue()}{if $rawObject->isDisableColumnValueString()}'{/if}{literal}"{/literal}{/if}>
{if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
</a>