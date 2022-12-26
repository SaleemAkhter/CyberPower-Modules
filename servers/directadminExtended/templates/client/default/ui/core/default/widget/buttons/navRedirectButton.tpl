<a class="{$rawObject->getClasses()}" {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
{if $rawObject->isRawTitle()}title="{$rawObject->getRawTitle()}"{elseif $rawObject->getTitle()}title="{$MGLANG->T('button', $rawObject->getTitle())}"{/if}>
{if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
<span class="lu-nav__link-text">{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('button', $rawObject->getTitle())}{/if}</span>
</a>