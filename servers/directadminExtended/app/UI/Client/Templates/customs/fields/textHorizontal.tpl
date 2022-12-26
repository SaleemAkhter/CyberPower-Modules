<div class="lu-form-group lu-form-group--horizontal lu-row mt-10">
    <label class="lu-form-label lu-col-md-3">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i>
        {/if}
    </label>

    <input class="lu-form-control lu-col-md-9" type="{if $rawObject->isPassword()}password{else}text{/if}" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}"
           value="{$rawObject->getValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if}
           {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    <div class="lu-form-feedback lu-form-feedback--icon lu-offset-md-2 lu-col-md-9" hidden="hidden">

    </div>
</div>
