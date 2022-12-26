<a class="{$rawObject->getClasses()}" href="#"
   {if $rawObject->isRawTitle()}title="{$rawObject->getRawTitle()}"{elseif $rawObject->getTitle()}title="{$MGLANG->T('button', $rawObject->getTitle())}"{/if}>
    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
    <span class="lu-nav__link-text">{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('button', $rawObject->getTitle())}{/if}</span>
    <span class="lu-nav__link-drop-arrow"></span>
</a>

<ul class="lu-nav lu-nav--sub">
    {foreach from=$rawObject->getButtons() key=subButtonKey item=subButton}
        {$subButton->getHtml()}
    {/foreach}
</ul>