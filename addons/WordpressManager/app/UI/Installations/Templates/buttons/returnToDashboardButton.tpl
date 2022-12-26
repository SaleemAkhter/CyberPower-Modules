<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
    class="{$rawObject->getClasses()}">
    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('button', $rawObject->getTitle())}{/if}
</a>
