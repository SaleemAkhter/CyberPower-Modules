<div class="lu-form-group row">
    <label for="staticEmail" class="{$rawObject->getLabelClass()}">
        {if $rawObject->getTextFieldTitle()}{$MGLANG->T($rawObject->getTextFieldTitle())}{/if}
    </label>
    <div class="{$rawObject->getFieldClass()}">
        <input class="lu-form-control" type="text" placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getTextFieldName()}"
        value="{$rawObject->getTextFieldValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if} {if $rawObject->getValue() === 'on'}disabled{/if}>
        <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden"></div>
    </div>

    <div class="{$rawObject->getSwitchClass()}">
        <div class="lu-form-check lu-m-b-2x">
            <label>
                <div class="lu-switch" {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach} style="margin-left: auto;">
                <input type="checkbox" class="lu-switch__checkbox" name="{$rawObject->getName()}" {if $rawObject->getValue() === 'on'}checked{/if}
                {if $rawObject->isDisabled()}disabled="disabled"{/if}>
                <span class="lu-switch__container"><span class="lu-switch__handle"></span></span>
            </div>
            <span class="lu-form-text">{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
            {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper lu-tooltip "></i>
            {/if}</span>
            </label>
        </div>

    </div>
</div>


