<div class="lu-form-group">
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
        {/if}
    </label>
    <input type="hidden" name="{$rawObject->getName()}" value="{$rawObject->getValue()}">
    <div {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>{$rawObject->getValue()} </div>
    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">

    </div>
</div>
