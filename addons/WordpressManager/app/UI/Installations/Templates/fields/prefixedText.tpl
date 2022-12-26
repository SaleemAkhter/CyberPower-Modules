<div class="lu-form-group {$rawObject->getFormGroupClass()}">
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i>
        {/if}
    </label>
    <div class="input-group">
      {if $rawObject->getPrefix()}<div class="input-group-addon">{$rawObject->getPrefix()}</div>{/if}
      <input type="text" class="lu-form-control"  placeholder="{$rawObject->getPlaceholder()}" name="{$rawObject->getName()}"
           value="{$rawObject->getValue()}" {if $rawObject->isDisabled()}disabled="disabled"{/if}
           {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>

    </div>

    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div>
