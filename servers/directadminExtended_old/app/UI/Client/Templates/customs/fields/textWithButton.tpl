<div class="lu-form-group">
    <label class="lu-form-label">
{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
{if $rawObject->getDescription()}
    <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper lu-tooltip "></i>
{/if}
</label>
<div class="lu-input-group">
    <input class="lu-form-control" type="text" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}"
           value="{$rawObject->getValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if}
           {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    {foreach from=$rawObject->getButtons() item=button}
        {$button->getHtml()}
    {/foreach}
</div>

<div class="form-feedback form-feedback--icon" hidden="hidden">
</div>    
</div>
