{if $rawObject->getValue()}

<div class="lu-form-group row">
    <label class="lu-form-label col-sm-4">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i>
        {/if}
    </label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext " id="{$rawObject->getId()}" value="{$rawObject->getValue()}">
    </div>
    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div>
{/if}
